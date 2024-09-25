<?php

declare(strict_types=1);

namespace App\Connectors\Article;

use App\Infastructure\HttpMethodsEnum;
use RuntimeException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

readonly class ArticleHttpClient
{
    private const string ARTICLES_ROUTE = '/posts';
    private const string ARTICLE_ROUTE = '/posts/%d';
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
    public function fetchArticleList(array $parameters = []): array
    {
        return $this->makeRequest(self::ARTICLES_ROUTE, 'GET', $parameters);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function fetchArticle(array $parameters = []): array
    {
        return $this->makeRequest(self::ARTICLE_ROUTE, 'GET', $parameters);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function makeRequest(string $route, string $method, array $parameters = [], array $body = []): array {
        $response = $this->httpClient->request(
            $method,
            $this->buildEndpoint($route, $parameters),
            $this->buildOptions($body),
        );

        return $this->getDecodedResponse($response);
    }

    private function buildOptions(array $body): array
    {
        return [
            'headers' => [
                'Content-type' => 'application/json'
            ],
            'body' => $body,
        ];
    }

    private function buildEndpoint(string $route, array $parameters = []): string
    {
        try {
            $endpoint = $this->apiUrl . sprintf($route, ...$parameters);
        } catch (\Exception) {
            throw new RuntimeException('Count of route template params and query params does not match');
        }

        return $endpoint;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function getDecodedResponse(ResponseInterface $response): array
    {
        return json_decode($response->getContent(), flags: JSON_OBJECT_AS_ARRAY);
    }
}