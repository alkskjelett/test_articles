<?php

namespace App\Service\Article;

use App\Connectors\Article\Filter\ArticleFilterContainer;

class ArticleFilterService
{
    public function filter(array $articles, ArticleFilterContainer $articleFilterContainer): array
    {
        if ($articleFilterContainer->authorId !== 0) {
            $articles = array_filter($articles, static fn(array $article) => $articleFilterContainer->authorId == $article['userId']);
        }

        return $articles;
    }
}