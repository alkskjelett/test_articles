<?php

declare(strict_types=1);

namespace App\Connectors\Article\Filter;

use Symfony\Component\HttpFoundation\Request;

final readonly class ArticleFilterContainer
{
    private function __construct(
        public int $authorId = 0,
    ) {
    }

    public static function create(Request $request): self
    {
        return new self(
            (int) $request->query->get('author_id') ?? 0,
        );
    }
}