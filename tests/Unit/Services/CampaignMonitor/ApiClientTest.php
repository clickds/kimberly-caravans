<?php

namespace Tests\Unit\Services\CampaignMonitor;

use App\Services\CampaignMonitor\ApiClient;
use CS_REST_Subscribers;
use CS_REST_Wrapper_Result;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

/**
 * @runTestsInSeparateProcesses
 * @preserveGlobalState disabled
 */
class ApiClientTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscribing_to_list(): void
    {
        $response = 'It worked';
        $result = Mockery::mock(CS_REST_Wrapper_Result::class);
        $result->response = $response;
        $result->shouldReceive('was_successful')->andReturn(true);
        $apiMock = Mockery::mock('overload:' . CS_REST_Subscribers::class);
        $apiMock->shouldReceive('add')->andReturn($result);

        $data = [
            'EmailAddress' => "test@example.com",
            'Name' => "Joe Bloggs",
            'Resubscribe' => true,
            'ConsentToTrack' => 'yes',
            'CustomFields' => [
                [
                    'Key' => "Title",
                    'Value' => "Mr",
                ],
                [
                    'Key' => "Home Telephone",
                    'Value' => "Home Phone",
                ],
                [
                    'Key' => "Mobile Telephone",
                    'Value' => "Mobile",
                ],
                [
                    'Key' => "Address1",
                    'Value' => "Address 1",
                ],
                [
                    'Key' => "Address2",
                    'Value' => "Address 2",
                ],
                [
                    'Key' => "Town/City",
                    'Value' => "City",
                ],
                [
                    'Key' => "County",
                    'Value' => "County",
                ],
                [
                    'Key' => "Postcode",
                    'Value' => "Postcode",
                ],
                [
                    'Key' => "Contact Preferences",
                    'Value' => "Email - Please confirm details",
                ],
                [
                    'Key' => "Contact Preferences",
                    'Value' => "Telephone - Please confirm details",
                ],
                [
                    'Key' => "Contact Preferences",
                    'Value' => "Post - Please confirm details",
                ],
                [
                    'Key' => "Contact Preferences",
                    'Value' => "Share with third parties - we will only pass on your details to carefully selected third parties",
                ],
                [
                    'Key' => "Relevant Information",
                    'Value' => "Caravan",
                ],
                [
                    'Key' => "Relevant Information",
                    'Value' => "Motorhome",
                ],
            ]
        ];

        $apiKey = env('CAMPAIGN_MONITOR_API_KEY');
        $apiClient = new ApiClient($apiKey);

        $apiResponse = $apiClient->subscribeToList('12345', $data);

        $this->assertEquals($response, $apiResponse);
    }
}
