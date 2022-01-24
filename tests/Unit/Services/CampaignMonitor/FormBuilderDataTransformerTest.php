<?php

namespace Tests\Unit\Services\CampaignMonitor;

use App\Models\BusinessArea;
use App\Models\Dealer;
use App\Models\Form;
use App\Models\Fieldset;
use App\Models\Field;
use App\Services\CampaignMonitor\FormBuilderDataTransformer as Transformer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FormBuilderDataTransformerTest extends TestCase
{
    use RefreshDatabase;

    public function test_transforms_data(): void
    {
        $form = factory(Form::class)->create();
        $fieldset = factory(Fieldset::class)->create();
        $form->fieldsets()->attach($fieldset);
        $email = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'crm_field_name' => Field::CRM_FIELD_EMAIL,
            'input_name' => 'email',
            'label' => 'email',
            'type' => FIELD::TYPE_EMAIL,
        ]);
        $name = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'crm_field_name' => Field::CRM_FIELD_NAME,
            'input_name' => 'name',
            'label' => 'name',
            'type' => FIELD::TYPE_TEXT,
        ]);
        $title = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'crm_field_name' => Field::CRM_FIELD_TITLE,
            'input_name' => 'title',
            'label' => 'title',
            'type' => FIELD::TYPE_TEXT,
        ]);
        $homePhone = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'crm_field_name' => Field::CRM_FIELD_HOME_PHONE,
            'input_name' => 'home_phone',
            'label' => 'home_phone',
            'type' => FIELD::TYPE_TEXT,
        ]);
        $mobile = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'crm_field_name' => Field::CRM_FIELD_MOBILE,
            'input_name' => 'mobile',
            'label' => 'mobile',
            'type' => FIELD::TYPE_TEXT,
        ]);
        $address1 = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'crm_field_name' => Field::CRM_FIELD_ADDRESS_1,
            'input_name' => 'address1',
            'label' => 'address1',
            'type' => FIELD::TYPE_TEXT,
        ]);
        $address2 = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'crm_field_name' => Field::CRM_FIELD_ADDRESS_2,
            'input_name' => 'address2',
            'label' => 'address2',
            'type' => FIELD::TYPE_TEXT,
        ]);
        $city = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'crm_field_name' => Field::CRM_FIELD_CITY,
            'input_name' => 'city',
            'label' => 'city',
            'type' => FIELD::TYPE_TEXT,
        ]);
        $county = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'crm_field_name' => Field::CRM_FIELD_COUNTY,
            'input_name' => 'county',
            'label' => 'county',
            'type' => FIELD::TYPE_TEXT,
        ]);
        $postcode = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'crm_field_name' => Field::CRM_FIELD_POSTCODE,
            'input_name' => 'postcode',
            'label' => 'postcode',
            'type' => FIELD::TYPE_TEXT,
        ]);

        $contactPreferences = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'crm_field_name' => Field::CRM_FIELD_CONTACT_PREFERENCES,
            'input_name' => 'contact_preferences',
            'label' => 'contact_preferences',
            'type' => FIELD::TYPE_MULTIPLE_CHECKBOXES,
            'options' => ['Email', 'Telephone', 'Post', 'Third Parties'],
        ]);
        $relevantInformation = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'crm_field_name' => Field::CRM_FIELD_RELEVANT_INFORMATION,
            'input_name' => 'relevant_information',
            'label' => 'relevant_information',
            'type' => FIELD::TYPE_MULTIPLE_CHECKBOXES,
            'options' => ['Caravan', 'Motorhome'],
        ]);
        $dealerField = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'crm_field_name' => Field::CRM_FIELD_PREFERRED_DEALER,
            'input_name' => 'dealer',
            'label' => 'dealer',
            'type' => FIELD::TYPE_DEALER_SELECT,
        ]);
        $dealer = factory(Dealer::class)->create();
        $businessAreaField = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'crm_field_name' => Field::CRM_FIELD_BUSINESS_AREA,
            'input_name' => 'business_area',
            'label' => 'business area',
            'type' => FIELD::TYPE_BUSINESS_AREA_SELECT,
        ]);
        $businessArea = factory(BusinessArea::class)->create();

        $data = [
            $email->input_name => 'test@example.com',
            $name->input_name => 'Joe Bloggs',
            $title->input_name => 'Mr',
            $homePhone->input_name => 'Home Phone',
            $mobile->input_name => 'Mobile',
            $address1->input_name => 'Address 1',
            $address2->input_name => 'Address 2',
            $city->input_name => 'City',
            $county->input_name => 'County',
            $postcode->input_name => 'Postcode',
            $contactPreferences->input_name => $contactPreferences->options,
            $relevantInformation->input_name => $relevantInformation->options,
            $dealerField->input_name => $dealer->name,
            $businessAreaField->input_name => $businessArea->name,
        ];

        $transformer = new Transformer($form, $data);

        $crmData = $transformer->call();

        $this->assertEquals('test@example.com', $crmData[Field::CRM_FIELD_EMAIL]);
        $this->assertEquals('Joe Bloggs', $crmData[Field::CRM_FIELD_NAME]);
        $this->assertEquals(true, $crmData['Resubscribe']);
        $this->assertArrayHasKey('CustomFields', $crmData);
        $customFields = $crmData['CustomFields'];
        $this->assertContains([
            'Key' => Field::CRM_FIELD_TITLE,
            'Value' => 'Mr',
        ], $customFields);
        $this->assertContains([
            'Key' => Field::CRM_FIELD_HOME_PHONE,
            'Value' => 'Home Phone',
        ], $customFields);
        $this->assertContains([
            'Key' => Field::CRM_FIELD_MOBILE,
            'Value' => 'Mobile',
        ], $customFields);
        $this->assertContains([
            'Key' => Field::CRM_FIELD_ADDRESS_1,
            'Value' => 'Address 1',
        ], $customFields);
        $this->assertContains([
            'Key' => Field::CRM_FIELD_ADDRESS_2,
            'Value' => 'Address 2',
        ], $customFields);
        $this->assertContains([
            'Key' => Field::CRM_FIELD_CITY,
            'Value' => 'City',
        ], $customFields);
        $this->assertContains([
            'Key' => Field::CRM_FIELD_COUNTY,
            'Value' => 'County',
        ], $customFields);
        $this->assertContains([
            'Key' => Field::CRM_FIELD_POSTCODE,
            'Value' => 'Postcode',
        ], $customFields);
        $this->assertContains([
            'Key' => Field::CRM_FIELD_PREFERRED_DEALER,
            'Value' => $dealer->name,
        ], $customFields);
        $this->assertContains([
            'Key' => Field::CRM_FIELD_BUSINESS_AREA,
            'Value' => $businessArea->name,
        ], $customFields);
        foreach ($contactPreferences->options as $option) {
            $value = $this->contactPreferenceValueForOption($option);
            $this->assertContains([
                'Key' => Field::CRM_FIELD_CONTACT_PREFERENCES,
                'Value' => $value,
            ], $customFields);
        }
        foreach ($relevantInformation->options as $option) {
            $this->assertContains([
                'Key' => Field::CRM_FIELD_RELEVANT_INFORMATION,
                'Value' => $option,
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
