<?php

namespace App\Services\CampaignMonitor;

use App\Models\Field;
use App\Models\VehicleEnquiry;

class VehicleEnquiryDataTransformer
{
    private VehicleEnquiry $vehicleEnquiry;

    public function __construct(VehicleEnquiry $vehicleEnquiry)
    {
        $this->vehicleEnquiry = $vehicleEnquiry;
    }

    public function call(): array
    {
        $crmData = [
            Field::CRM_FIELD_EMAIL => $this->vehicleEnquiry->email,
            Field::CRM_FIELD_NAME => $this->vehicleEnquiry->name(),
            'Resubscribe' => true,
            'ConsentToTrack' => 'yes'
        ];
        $customFields = [
            [
                'Key' => Field::CRM_FIELD_TITLE,
                'Value' => $this->vehicleEnquiry->title,
            ],
            [
                'Key' => Field::CRM_FIELD_HOME_PHONE,
                'Value' => $this->vehicleEnquiry->phone_number,
            ],
            [
                'Key' => Field::CRM_FIELD_COUNTY,
                'Value' => $this->vehicleEnquiry->county,
            ],
        ];
        foreach ($this->vehicleEnquiry->dealers ?? [] as $dealer) {
            $customFields[] = [
                'Key' => Field::CRM_FIELD_PREFERRED_DEALERS,
                'Value' => $dealer->name,
            ];
        }
        foreach ($this->vehicleEnquiry->interests ?? [] as $interest) {
            $customFields[] = [
                'Key' => Field::CRM_FIELD_RELEVANT_INFORMATION,
                'Value' => $interest,
            ];
        }
        foreach ($this->vehicleEnquiry->marketing_preferences ?? [] as $marketingPreference) {
            $value = $this->transformValueForContactPreferences($marketingPreference);
            $customFields[] = [
                'Key' => Field::CRM_FIELD_CONTACT_PREFERENCES,
                'Value' => $value,
            ];
        }

        $crmData['CustomFields'] = $customFields;

        return $crmData;
    }

    private function transformValueForContactPreferences(string $value): string
    {
        $value = strtolower($value);
        if (strpos($value, 'email') !== false) {
            return 'Email - Please confirm details';
        }
        if (strpos($value, 'post') !== false) {
            return 'Post - Please confirm details';
        }
        if (strpos($value, 'phone') !== false) {
            return 'Telephone - Please confirm details';
        }
        return "Share with third parties - we will only pass on your details to carefully selected third parties";
    }
}
