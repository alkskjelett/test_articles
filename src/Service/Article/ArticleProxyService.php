<?php

namespace App\Service\Article;

use App\Connectors\Article\ArticleHttpClient;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

readonly class ArticleProxyService implements ArticleProxyInterface
{
    public function __construct(
        private ArticleHttpClient $articleHttpClient,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getArticlesList(): array
    {
        return $this->articleHttpClient->fetchArticleList();
    }
}