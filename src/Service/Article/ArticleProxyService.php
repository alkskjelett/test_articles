<?php

declare(strict_types=1);

namespace App\Service\Article;

use App\Connectors\Article\ArticleHttpClient;
use App\Connectors\Article\Filter\ArticleFilterContainer;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

readonly class ArticleProxyService implements ArticleProxyServiceInterface
{
    public function __construct(
        private ArticleHttpClient $articleHttpClient,
        private ArticleFilterService $articleFilterService,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getArticlesList(ArticleFilterContainer $articleFilter): array
    {
        $articles = $this->articleHttpClient->fetchArticleList();

        return $this->articleFilterService->filter($articles, $articleFilter);
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getArticleById(int $id): array
    {
        return $this->articleHttpClient->fetchArticle([$id]);
    }
}