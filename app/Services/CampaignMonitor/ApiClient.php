<?php

namespace App\Services\CampaignMonitor;

use CS_REST_Subscribers;
use CS_REST_Wrapper_Result;
use Exception;
use Illuminate\Support\Facades\Log;

class ApiClient
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @return mixed
     */
    public function subscribeToList(string $listId, array $data)
    {
        $client = new CS_REST_Subscribers($listId, $this->getAuth());
        $result = $client->add($data);
        return $this->handleResult($result, 'Could not subscribe to list');
    }

    /**
     * @return mixed
     */
    private function handleResult(
        CS_REST_Wrapper_Result $result,
        string $errorMessage = 'Request to campaign monitor failed'
    ) {
        if ($result->was_successful()) {
            return $result->response;
        }
        Log::error($result->response->message);
        throw new Exception($errorMessage);
    }

    private function getAuth(): array
    {
        return [
            'api_key' => $this->apiKey,
        ];
    }

    private function getApiKey(): string
    {
        return $this->apiKey;
    }
}
