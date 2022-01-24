<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\FormSubmissionsRequest;
use App\Mail\NewFormSubmission;
use App\Models\BusinessArea;
use App\Models\Field;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\SiteSetting;
use App\Services\CampaignMonitor\ApiClient;
use App\Services\CampaignMonitor\FormBuilderDataTransformer;
use App\Services\Site\FormSubmissionSaver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Throwable;

class FormSubmissionsController extends Controller
{
    private FormSubmissionSaver $formSubmissionSaver;

    public function __construct(FormSubmissionSaver $formSubmissionSaver)
    {
        $this->formSubmissionSaver = $formSubmissionSaver;
    }

    public function __invoke(FormSubmissionsRequest $request, Form $form): RedirectResponse
    {
        try {
            $formSubmission = $this->formSubmissionSaver->save($form, $request->validated());
            $this->sendToCampaignMonitor($form, $request->validated());
            $this->sendMail($form, $formSubmission);

            return redirect()
                ->back()
                ->with('success', $form->successful_submission_message);
        } catch (Throwable $e) {
            Log::error($e);

            return redirect()
                ->back()
                ->withInput($request->all())
                ->withErrors('Something went wrong when submitting the form');
        }
    }

    private function sendMail(Form $form, FormSubmission $formSubmission): void
    {
        try {
            $mailable = new NewFormSubmission($formSubmission);

            $this->attachFiles($mailable, $formSubmission);

            $ccEmailAddresses = array_merge(
                $form->carbonCopyRecipients()->toBase()->pluck('email')->toArray(),
                $this->getBusinessAreaEmailAddresses($form, $formSubmission)
            );

            $formSubmissionDefaultEmailAddress = $this->getFormSubmissionDefaultEmailAddress();

            if (!is_null($formSubmissionDefaultEmailAddress)) {
                $ccEmailAddresses[] = $formSubmissionDefaultEmailAddress->value;
            }

            Mail::to($form->email_to)->cc($ccEmailAddresses)->send($mailable);
        } catch (Throwable $e) {
            Log::error($e);
        }
    }

    private function sendToCampaignMonitor(Form $form, array $submissionData): void
    {
        try {
            $dataTransformer = new FormBuilderDataTransformer($form, $submissionData);
            $crmData = $dataTransformer->call();
            $site = resolve('currentSite');

            if (is_null($site)) {
                return;
            }

            $listId = $site->campaign_monitor_list_id;

            if (is_null($listId)) {
                return;
            }

            $apiClient = App::make(ApiClient::class);
            $apiClient->subscribeToList($listId, $crmData);
        } catch (Throwable $e) {
            Log::error($e);
        }
    }

    private function attachFiles(Mailable $mailable, FormSubmission $submission): void
    {
        $filesToAttach = $submission->getMedia('file-uploads');

        $filesToAttach->map(function (Media $media) use ($mailable) {
            $mailable->attach($media->getFullUrl());
        });
    }

    private function getBusinessAreaEmailAddresses(Form $form, FormSubmission $formSubmission): array
    {
        $formFieldsetIds = $form->fieldsets()->pluck('id');

        $businessAreaFieldLabels = Field::whereIn('fieldset_id', $formFieldsetIds)
            ->where('type', Field::TYPE_BUSINESS_AREA_SELECT)
            ->pluck('label');

        $businessAreaNames = [];

        foreach ($businessAreaFieldLabels as $businessAreaFieldLabel) {
            $businessAreaNames[] = $formSubmission->getSubmissionDataValue($businessAreaFieldLabel);
        }

        return BusinessArea::whereIn('name', $businessAreaNames)->get()->pluck('email')->toArray();
    }

    private function getFormSubmissionDefaultEmailAddress(): ?SiteSetting
    {
        return SiteSetting::formSubmissionDefaultEmailAddress()->first();
    }
}
