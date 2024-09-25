<?php

declare(strict_types=1);

namespace App\Service\Author;

use App\Connectors\Author\AuthorHttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

readonly class AuthorProxyService implements AuthorProxyServiceInterface
{
    public function __construct(
        private AuthorHttpClient $authorHttpClient,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getAuthorsList(): array
    {
        return $this->authorHttpClient->fetchAuthorList();
    }
}