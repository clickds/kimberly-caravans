<?php

namespace Tests\Feature\Api\VehicleEnquiries;

use App\Mail\VehicleEnquiry as VehicleEnquiryMail;
use App\Models\Dealer;
use App\Models\EmailRecipient;
use App\Models\Site;
use App\Models\VehicleEnquiry;
use App\Services\CampaignMonitor\ApiClient;
use CS_REST_Wrapper_Result;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Mail;
use Illuminate\Testing\TestResponse;
use Mockery;
use Tests\TestCase;

class StoreTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function setUp(): void
    {
        parent::setUp();
        factory(Site::class)->state('default')->create();
    }

    /**
     * @dataProvider requiredProvider
     */
    public function test_required_validation(string $inputName): void
    {
        $data = $this->validData([
            $inputName => null,
        ]);

        $response = $this->submit($data);

        $response->assertJsonValidationErrors($inputName);
    }

    public function requiredProvider(): array
    {
        return [
            ['first_name'],
            ['surname'],
            ['email'],
            ['phone_number'],
            ['county'],
            ['message'],
        ];
    }

    public function test_email_validation(): void
    {
        $data = $this->validData([
            'email' => 'abc',
        ]);

        $response = $this->submit($data);

        $response->assertJsonValidationErrors('email');
    }

    public function test_exists_validation(): void
    {
        $data = $this->validData([
            'dealer_ids' => [0],
        ]);

        $response = $this->submit($data);

        $response->assertJsonValidationErrors('dealer_ids.0');
    }

    public function test_sends_to_default_email(): void
    {
        Mail::fake();
        $mock = Mockery::mock(ApiClient::class);
        $resultMock = Mockery::mock(CS_REST_Wrapper_Result::class);
        $mock->shouldReceive('subscribeToList')->andReturn($resultMock);
        $this->app->bind(ApiClient::class, function () use ($mock) {
            return $mock;
        });
        $helpMethods = ['a', 'b'];
        $interests = ['caravans', 'motorhomes'];
        $marketingPreferences = ['email'];
        $data = $this->validData([
            'help_methods' => $helpMethods,
            'interests' => $interests,
            'marketing_preferences' => $marketingPreferences,
        ]);

        $response = $this->submit($data);

        $response->assertStatus(201);
        Mail::assertSent(VehicleEnquiryMail::class);
        $enquiryData = Arr::only($data, [
            'first_name',
            'surname',
            'email',
            'phone_number',
            'county',
            'message',
            'dealer_id',
        ]);
        $this->assertDatabaseHas('vehicle_enquiries', $enquiryData);
        $vehicleEnquiry = VehicleEnquiry::first();
        $this->assertEquals($helpMethods, $vehicleEnquiry->help_methods);
        $this->assertEquals($interests, $vehicleEnquiry->interests);
        $this->assertEquals($marketingPreferences, $vehicleEnquiry->marketing_preferences);
    }

    public function test_sends_email_to_email_recipient_who_recieves_vehicle_enquiry(): void
    {
        Mail::fake();
        $emailRecipient = factory(EmailRecipient::class)->create([
            'receives_vehicle_enquiry' => true,
        ]);
        $mock = Mockery::mock(ApiClient::class);
        $resultMock = Mockery::mock(CS_REST_Wrapper_Result::class);
        $mock->shouldReceive('subscribeToList')->andReturn($resultMock);
        $this->app->bind(ApiClient::class, function () use ($mock) {
            return $mock;
        });
        $helpMethods = ['a', 'b'];
        $interests = ['caravans', 'motorhomes'];
        $marketingPreferences = ['email'];
        $data = $this->validData([
            'help_methods' => $helpMethods,
            'interests' => $interests,
            'marketing_preferences' => $marketingPreferences,
        ]);

        $response = $this->submit($data);

        $response->assertStatus(201);
        Mail::assertSent(VehicleEnquiryMail::class, function ($mail) use ($emailRecipient) {
            return $mail->hasTo($emailRecipient->email);
        });
        $enquiryData = Arr::only($data, [
            'first_name',
            'surname',
            'email',
            'phone_number',
            'county',
            'message',
            'dealer_id',
        ]);
        $this->assertDatabaseHas('vehicle_enquiries', $enquiryData);
        $vehicleEnquiry = VehicleEnquiry::first();
        $this->assertEquals($helpMethods, $vehicleEnquiry->help_methods);
        $this->assertEquals($interests, $vehicleEnquiry->interests);
        $this->assertEquals($marketingPreferences, $vehicleEnquiry->marketing_preferences);
    }

    private function validData(array $overrides = []): array
    {
        $defaults = [
            'title' => $this->faker->title(),
            'first_name' => $this->faker->firstName,
            'surname' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone_number' => $this->faker->phoneNumber,
            'county' => $this->faker->country,
            'message' => $this->faker->sentence(),
        ];

        if (!array_key_exists('dealer_ids', $overrides)) {
            $defaults['dealer_ids'] = factory(Dealer::class, 2)->create()->pluck('id')->toArray();
        }

        return array_merge($defaults, $overrides);
    }

    private function submit(array $data): TestResponse
    {
        $url = route('api.vehicle-enquiries.store');

        return $this->postJson($url, $data);
    }
}
