<?php

namespace Tests\Unit\Services\CampaignMonitor;

use App\Models\Dealer;
use App\Models\Field;
use App\Models\VehicleEnquiry;
use App\Services\CampaignMonitor\VehicleEnquiryDataTransformer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VehicleEnquiryDataTransformerTest extends TestCase
{
    use RefreshDatabase;

    public function test_transforms_data(): void
    {
        $vehicleEnquiry = factory(VehicleEnquiry::class)->create([
            'interests' => ['caravans', 'motorhomes'],
            'marketing_preferences' => ['email', 'telephone', 'third party'],
        ]);
        $dealers = factory(Dealer::class, 2)->create();
        $vehicleEnquiry->dealers()->sync($dealers->pluck('id'));

        $transformer = new VehicleEnquiryDataTransformer($vehicleEnquiry);

        $crmData = $transformer->call();

        $this->assertEquals($vehicleEnquiry->email, $crmData[Field::CRM_FIELD_EMAIL]);
        $this->assertEquals($vehicleEnquiry->name(), $crmData[Field::CRM_FIELD_NAME]);
        $this->assertEquals(true, $crmData['Resubscribe']);
        $this->assertArrayHasKey('CustomFields', $crmData);
        $customFields = $crmData['CustomFields'];
        $this->assertContains([
            'Key' => Field::CRM_FIELD_TITLE,
            'Value' => $vehicleEnquiry->title,
        ], $customFields);
        $this->assertContains([
            'Key' => Field::CRM_FIELD_HOME_PHONE,
            'Value' => $vehicleEnquiry->phone_number,
        ], $customFields);
        $this->assertContains([
            'Key' => Field::CRM_FIELD_COUNTY,
            'Value' => $vehicleEnquiry->county,
        ], $customFields);
        foreach ($vehicleEnquiry->interests as $interest) {
            $this->assertContains([
                'Key' => Field::CRM_FIELD_RELEVANT_INFORMATION,
                'Value' => $interest,
            ], $customFields);
        }
        foreach ($vehicleEnquiry->dealers as $dealer) {
            $this->assertContains([
                'Key' => Field::CRM_FIELD_PREFERRED_DEALERS,
                'Value' => $dealer->name,
            ], $customFields);
        }
        foreach ($vehicleEnquiry->marketing_preferences as $marketingPreference) {
            $value = $this->contactPreferenceValueForOption($marketingPreference);
            $this->assertContains([
                'Key' => Field::CRM_FIELD_CONTACT_PREFERENCES,
                'Value' => $value,
            ], $customFields);
        }
    }

    private function contactPreferenceValueForOption(string $option): string
    {
        switch ($option) {
            case 'email':
                return 'Email - Please confirm details';
            case 'post':
                return 'Post - Please confirm details';
            case 'phone':
                return 'Telephone - Please confirm details';
            default:
                return "Share with third parties - we will only pass on your details to carefully selected third parties";
        }
    }
}
