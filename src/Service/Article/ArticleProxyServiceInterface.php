<?php

declare(strict_types=1);

namespace App\Service\Article;

use App\Connectors\Article\Filter\ArticleFilterContainer;

interface ArticleProxyServiceInterface
{
    public function getArticlesList(ArticleFilterContainer $articleFilter): array;
    public function getArticleById(int $id): array;

}