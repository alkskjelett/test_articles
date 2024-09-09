<?php

namespace App\Service\Http;

use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

readonly class ArticleHttpClient
{
    public function __construct(
        private string $apiUrl,
        private HttpClientInterface $httpClient,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function fetchArticleList(): array
    {
        $response = $this->httpClient->request(
            'GET',
            $this->apiUrl
        );

        return json_decode($response->getContent());
    }
}