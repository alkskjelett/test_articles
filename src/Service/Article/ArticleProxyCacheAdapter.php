<?php

declare(strict_types=1);

namespace App\Service\Article;

use App\Connectors\Article\Filter\ArticleFilterContainer;
use Psr\Cache\CacheException;
use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

readonly class ArticleProxyCacheAdapter implements ArticleProxyServiceInterface
{
    public const string CACHE_ARTICLES_KEY = 'articles_author_%d';
    public const string CACHE_ARTICLE_KEY = 'article_%d';
    public const int CACHE_ARTICLES_EXPIRING_TIME = 60 * 2; // 2 mins
    public const int CACHE_ARTICLE_EXPIRING_TIME = 60 * 5; // 5 mins

    public function __construct(
        private ArticleProxyService $articleProxyService,
        private CacheItemPoolInterface $cacheItemPool,
    ) {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws InvalidArgumentException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws CacheException
     * @throws ClientExceptionInterface
     */
    public function getArticlesList(ArticleFilterContainer $articleFilter): array
    {
        $cacheItem = $this->cacheItemPool->getItem(sprintf(self::CACHE_ARTICLES_KEY, $articleFilter->authorId));
        if (! $cacheItem->isHit()) {
            $articles = $this->articleProxyService->getArticlesList($articleFilter);
            $cacheItem
                ->set($articles)
                ->expiresAfter(self::CACHE_ARTICLES_EXPIRING_TIME)
            ;
            $this->cacheItemPool->save($cacheItem);
        }

        return $cacheItem->get();
    }

    /**
     * @throws TransportExceptionInterface
     * @throws InvalidArgumentException
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getArticleById(int $id): array
    {
        $cacheItem = $this->cacheItemPool->getItem(sprintf(self::CACHE_ARTICLE_KEY, $id));
        if (! $cacheItem->isHit()) {
            $articles = $this->articleProxyService->getArticleById($id);
            $cacheItem
                ->set($articles)
                ->expiresAfter(self::CACHE_ARTICLE_EXPIRING_TIME)
            ;
            $this->cacheItemPool->save($cacheItem);
        }

        return $cacheItem->get();
    }
}