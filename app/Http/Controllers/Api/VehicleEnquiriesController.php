<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\VehicleEnquiries\StoreRequest;
use App\Mail\VehicleEnquiry as VehicleEnquiryMail;
use App\Models\EmailRecipient;
use App\Models\VehicleEnquiry;
use App\Services\CampaignMonitor\ApiClient;
use App\Services\CampaignMonitor\VehicleEnquiryDataTransformer;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class VehicleEnquiriesController extends Controller
{
    public function store(StoreRequest $request): JsonResponse
    {
        $data = $request->validated();
        $vehicleEnquiry = VehicleEnquiry::create($data);
        $vehicleEnquiry->dealers()->sync($data['dealer_ids']);

        $this->sendToCampaignMonitor($vehicleEnquiry);
        $this->sendEmail($vehicleEnquiry);

        return new JsonResponse(null, 201);
    }

    private function sendToCampaignMonitor(VehicleEnquiry $vehicleEnquiry): void
    {
        try {
            $dataTransformer = new VehicleEnquiryDataTransformer($vehicleEnquiry);
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

    private function sendEmail(VehicleEnquiry $vehicleEnquiry): void
    {
        $emails = $this->emailAddresses();
        try {
            foreach ($emails as $email) {
                $mailer = new VehicleEnquiryMail($vehicleEnquiry);
                Mail::to($email)->send($mailer);
            }
        } catch (Throwable $e) {
            Log::error($e);
        }
    }

    private function emailAddresses(): array
    {
        $emailAddresses = EmailRecipient::receivesVehicleEnquiry()->toBase()
            ->pluck('email')->toArray();
        if (empty($emailAddresses)) {
            if ($email = env('DEFAULT_VEHICLE_ENQUIRY_FORM_EMAIL')) {
                $emailAddresses[] = $email;
            }
        }

        return $emailAddresses;
    }
}
