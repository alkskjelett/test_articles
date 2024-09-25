<?php

declare(strict_types=1);

namespace App\Service\Author;

interface AuthorProxyServiceInterface
{
    public function getAuthorsList(): array;
}