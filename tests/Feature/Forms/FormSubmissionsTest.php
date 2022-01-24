<?php

namespace Tests\Feature\Forms;

use App\Mail\NewFormSubmission;
use App\Models\BusinessArea;
use App\Models\Dealer;
use App\Models\EmailRecipient;
use App\Models\Field;
use App\Models\Fieldset;
use App\Models\Form;
use App\Models\FormSubmission;
use App\Models\Site;
use App\Services\CampaignMonitor\ApiClient;
use CS_REST_Wrapper_Result;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\TestResponse;
use Mockery;
use Tests\TestCase;

class FormSubmissionsTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        factory(Site::class)->state('default')->create();
    }

    public function test_required_validation(): void
    {
        $form = factory(Form::class)->create();
        $fieldset = factory(Fieldset::class)->create();
        $form->fieldsets()->attach($fieldset);
        $field = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'input_name' => 'input_name',
            'type' => Field::TYPE_TEXT,
        ]);
        $data = [
            $field->input_name => null,
        ];

        $response = $this->submit($form, $data);

        $response->assertSessionHasErrors($field->input_name);
    }

    public function test_email_validation(): void
    {
        $form = factory(Form::class)->create();
        $fieldset = factory(Fieldset::class)->create();
        $form->fieldsets()->attach($fieldset);
        $field = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'input_name' => 'input_name',
            'type' => Field::TYPE_EMAIL,
        ]);
        $data = [
            $field->input_name => 'abc',
        ];

        $response = $this->submit($form, $data);

        $response->assertSessionHasErrors($field->input_name);
    }

    /**
     * @dataProvider multipleOptionProvider
     */
    public function test_in_options_validation_multiple_allowed(string $fieldType): void
    {
        $form = factory(Form::class)->create();
        $fieldset = factory(Fieldset::class)->create();
        $form->fieldsets()->attach($fieldset);
        $field = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'input_name' => 'input_name',
            'type' => $fieldType,
            'options' => ['a', 'b', 'c'],
        ]);
        $data = [
            $field->input_name => ['abc'],
        ];

        $response = $this->submit($form, $data);

        $response->assertSessionHasErrors($field->input_name . '.*');
    }

    public function multipleOptionProvider(): array
    {
        return [
            [Field::TYPE_MULTIPLE_CHECKBOXES],
        ];
    }

    /**
     * @dataProvider singleOptionProvider
     */
    public function test_in_options_validations(string $fieldType): void
    {
        $form = factory(Form::class)->create();
        $fieldset = factory(Fieldset::class)->create();
        $form->fieldsets()->attach($fieldset);
        $field = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'input_name' => 'input_name',
            'type' => $fieldType,
            'options' => ['a', 'b', 'c'],
        ]);
        $data = [
            $field->input_name => 'abc',
        ];

        $response = $this->submit($form, $data);

        $response->assertSessionHasErrors($field->input_name);
    }

    public function singleOptionProvider(): array
    {
        return [
            [Field::TYPE_RADIO_BUTTONS],
            [Field::TYPE_SELECT],
        ];
    }

    public function test_successfully_submits(): void
    {
        $this->fakeStorage();
        Mail::fake();
        $mock = Mockery::mock(ApiClient::class);
        $resultMock = Mockery::mock(CS_REST_Wrapper_Result::class);
        $mock->shouldReceive('subscribeToList')->andReturn($resultMock);
        $this->app->bind(ApiClient::class, function () use ($mock) {
            return $mock;
        });
        $form = factory(Form::class)->create();
        $emailRecipient = factory(EmailRecipient::class)->create();
        $form->carbonCopyRecipients()->attach($emailRecipient);
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

        $fileUploadField = factory(Field::class)->create([
            'fieldset_id' => $fieldset->id,
            'required' => true,
            'crm_field_name' => 'N/A',
            'input_name' => 'file_upload',
            'label' => 'file upload',
            'type' => FIELD::TYPE_FILE_UPLOAD,
        ]);
        $uploadedFile = UploadedFile::fake()->create('example_file.pdf', 100);

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
            $fileUploadField->input_name => $uploadedFile,
        ];

        $response = $this->submit($form, $data);

        $response->assertRedirect();
        $submission = FormSubmission::first();
        $this->assertNotNull($submission);
        $this->assertEquals($form->id, $submission->form_id);

        $submissionData = $submission->submission_data;
        $this->assertEquals('test@example.com', $submissionData[$email->label]);
        $this->assertEquals('Mr', $submissionData[$title->label]);
        $this->assertEquals('Joe Bloggs', $submissionData[$name->label]);
        $this->assertEquals('Home Phone', $submissionData[$homePhone->label]);
        $this->assertEquals('Mobile', $submissionData[$mobile->label]);
        $this->assertEquals('Address 1', $submissionData[$address1->label]);
        $this->assertEquals('Address 2', $submissionData[$address2->label]);
        $this->assertEquals('City', $submissionData[$city->label]);
        $this->assertEquals('County', $submissionData[$county->label]);
        $this->assertEquals('Postcode', $submissionData[$postcode->label]);
        $this->assertEquals($contactPreferences->options, $submissionData[$contactPreferences->label]);
        $this->assertEquals($relevantInformation->options, $submissionData[$relevantInformation->label]);
        $this->assertEquals($dealer->name, $submissionData[$dealerField->label]);
        $this->assertEquals($businessArea->name, $submissionData[$businessAreaField->label]);
        $this->assertEquals($uploadedFile->getClientOriginalName(), $submissionData[$fileUploadField->label]);

        Mail::assertSent(NewFormSubmission::class, function ($mail) use ($form, $emailRecipient, $businessArea) {
            return $mail->hasTo($form->email_to) && $mail->hasCc([$emailRecipient->email, $businessArea->email]) && !empty($mail->attachments);
        });
    }

    private function submit(Form $form, array $data = []): TestResponse
    {
        $url = route('form-submissions', $form);

        return $this->post($url, $data);
    }
}
