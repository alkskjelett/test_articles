<?php

namespace App\Connectors\Author;

use App\Infastructure\HttpMethodsEnum;
use RuntimeException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

readonly class AuthorHttpClient
{
    private const string ROUTE_AUTHORS = '/users';

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
    public function fetchAuthorList(): array
    {
        return $this->makeRequest(self::ROUTE_AUTHORS, HttpMethodsEnum::GET->value);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    private function makeRequest(string $route, string $method, array $parameters = []): array {
        $response = $this->httpClient->request(
            $method,
            $this->buildEndpoint($route, $parameters)

        );

        return $this->getDecodedResponse($response);
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