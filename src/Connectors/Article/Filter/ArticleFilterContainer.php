<?php

declare(strict_types=1);

namespace App\Connectors\Article\Filter;

final readonly class ArticleFilter
{
    public function __construct(
        public int $authorId,
    ) {
    }
}