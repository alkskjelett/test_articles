<?php

namespace App\Service\Http;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class AuthorHttpClient
{
    public function __construct(
        private string $apiUrl,
        private HttpClientInterface $httpClient,
    ) {
    }

    public function fetchAuthorList(): array
    {
        $response = $this->httpClient->request(
            'GET',
            $this->apiUrl
        );

        return json_decode($response->getContent());
    }
}