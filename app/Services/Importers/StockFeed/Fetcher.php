<?php

namespace App\Services\Importers\StockFeed;

use GuzzleHttp\Client;

class Fetcher
{
    /**
     * @var \GuzzleHttp\Client
     */
    private $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Note: Guzzle defaults to http_errors being set to true which will throw an error if a
     * 400 or 500 status code is returned
     */
    public function getFeed(): array
    {
        $response = $this->getClient()->get('rpcq');
        $responseBody = $response->getBody()->getContents();
        return json_decode($responseBody, true);
    }

    /**
     * Base64 Encoding to utilise Spatie Media Libraries addFromBase64Media
     */
    public function getImage(string $imageId): array
    {
        $response = $this->getClient()->get('img', [
            'query' => [
                'ref' => $imageId,
            ],
        ]);

        return [
            'mime_type' => $response->getHeaderLine('Content-Type'),
            'data' => base64_encode($response->getBody()->getContents()),
        ];
    }

    private function getClient(): Client
    {
        return $this->client;
    }
}
