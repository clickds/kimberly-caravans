<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\Site\StoreVacancyApplicationRequest;
use App\Mail\NewVacancyApplication;
use App\Models\SiteSetting;
use App\Models\Vacancy;
use App\Models\VacancyApplication;
use App\Services\Site\VacancyApplicationSaver;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class VacancyApplicationsController extends Controller
{
    private VacancyApplicationSaver $vacancyApplicationSaver;

    public function __construct(VacancyApplicationSaver $vacancyApplicationSaver)
    {
        $this->vacancyApplicationSaver = $vacancyApplicationSaver;
    }

    public function __invoke(StoreVacancyApplicationRequest $request, Vacancy $vacancy): RedirectResponse
    {
        try {
            $application = $this->vacancyApplicationSaver->save($vacancy, $request->all());

            $this->sendNotificationEmail($vacancy, $application);

            return redirect()
                ->back()
                ->with('success', 'Successfully submitted your application');
        } catch (Throwable $e) {
            Log::error($e);

            return redirect()
                ->back()
                ->withInput($request->all())
                ->withErrors('Something went wrong when submitting your application');
        }
    }

    private function sendNotificationEmail(Vacancy $vacancy, VacancyApplication $application): void
    {
        if (is_null($vacancy->notification_email_address)) {
            return;
        }

        $mailer = new NewVacancyApplication($application);

        $pendingMail = Mail::to($vacancy->notification_email_address);

        $formSubmissionDefaultEmailAddress = $this->getFormSubmissionDefaultEmailAddress();

        if (!is_null($formSubmissionDefaultEmailAddress)) {
            $pendingMail->cc([$formSubmissionDefaultEmailAddress->value]);
        }

        $pendingMail->send($mailer);
    }

    private function getFormSubmissionDefaultEmailAddress(): ?SiteSetting
    {
        return SiteSetting::formSubmissionDefaultEmailAddress()->first();
    }
}
