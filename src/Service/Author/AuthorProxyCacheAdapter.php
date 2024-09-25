<?php

declare(strict_types=1);

namespace App\Service\Author;

use Psr\Cache\CacheItemPoolInterface;
use Psr\Cache\InvalidArgumentException;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;

readonly class AuthorProxyCacheAdapter implements AuthorProxyServiceInterface
{
    private const string CACHE_KEY = 'authors';
    private const int CACHE_EXPIRING_TIME = 60 * 5; //5 mins

    public function __construct(
        private AuthorProxyService $authorProxyService,
        private CacheItemPoolInterface $cacheItemPool,
    )
    {
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws InvalidArgumentException
     * @throws RedirectionExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function getAuthorsList(): array
    {
        $cacheItem = $this->cacheItemPool->getItem(self::CACHE_KEY);
        if (! $cacheItem->isHit()) {
            $authors = $this->authorProxyService->getAuthorsList();
            $cacheItem
                ->set($authors)
                ->expiresAfter(self::CACHE_EXPIRING_TIME)
            ;
            $this->cacheItemPool->save($cacheItem);
        }

        return $cacheItem->get();
    }
}