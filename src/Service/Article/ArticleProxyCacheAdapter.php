<?php

namespace App\Service\Article;

use Psr\Cache\CacheException;
use Psr\Cache\InvalidArgumentException;
use Symfony\Component\Cache\Adapter\RedisAdapter;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

readonly class ArticleProxyCacheAdapter implements ArticleProxyInterface
{
    public const string CACHE_KEY = 'articles';
    public const string CACHE_TAG = 'articles_list';
    public const int CACHE_EXPIRING_TIME = 60 * 2; // 2 mins

    public function __construct(
        private ArticleProxyService $articleProxyService,
        private RedisAdapter $redisAdapter,
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
    public function getArticlesList(): array
    {
        $cacheItem = $this->redisAdapter->getItem(self::CACHE_KEY);
        if (! $cacheItem->isHit()) {
            $articles = $this->articleProxyService->getArticlesList();
            $cacheItem
                ->set($articles)
                ->expiresAfter(self::CACHE_EXPIRING_TIME)
                ->tag(self::CACHE_TAG)
            ;
        }
        return $cacheItem->get();
    }
}