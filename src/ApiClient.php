<?php

namespace app;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Response;

class ApiClient
{
    private const MAX_ATTEMPTS = 10;

    private Client $httpClient;

    public function __construct(Client $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function fetch(string $uri): array
    {
        $response = $this->get($uri);

        if (!$response) {
            return [];
        }

        return json_decode($response->getBody()->getContents(), true);
    }

    private function get(string $uri, int $attempts = 1): ?Response
    {
        try {
            $response = $this->httpClient->get($uri);
        } catch (RequestException $e) {
            error_log($e->getMessage());
            $response = null;

            if ($attempts >= self::MAX_ATTEMPTS) {
                echo "Gave up after $attempts attempts...\n";
            } else {
                $attempts += 1;
                echo "Try #$attempts attempt in 1 sec...\n";
                sleep(1);
                return $this->get($uri, $attempts);
            }
        }

        return $response;
    }
}
