<?php

namespace Tests\Support;

use GuzzleHttp\{
    Client,
    Handler\MockHandler,
    HandlerStack,
    Psr7\Response
};
use Illuminate\Support\Arr;

trait GuzzleMocks
{
    private function createGuzzleWithMockedResponses($responses): Client
    {
        $responses = is_array($responses) ?: array($responses);
        $mock = new MockHandler($responses);
        $handlerStack = HandlerStack::create($mock);
        return new Client(['handler' => $handlerStack]);
    }

    private function createGuzzleResponse(array $params)
    {
        $statusCode = Arr::get($params, 'status_code', 200);
        $headers = Arr::get($params, 'headers', []);
        $body = Arr::get($params, 'body', '');
        $protocol = Arr::get($params, 'protocol', 1.1);

        return new Response($statusCode, $headers, $body, $protocol);
    }
}
