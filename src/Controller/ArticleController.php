<?php

declare(strict_types=1);

namespace App\Controller;

use App\Connectors\Article\Filter\ArticleFilterContainer;
use App\Infastructure\JsonResponse;
use App\Service\Article\ArticleProxyServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    public function __construct(
        private readonly ArticleProxyServiceInterface $articleProxyService,
    ) {
    }

    #[Route(path: '/api/v1/articles', name: 'articles_list', methods: ['GET'])]
    public function index_list(Request $request): Response
    {
        $filterContainer = ArticleFilterContainer::create($request);
        $articles = $this->articleProxyService->getArticlesList($filterContainer);
        return new JsonResponse($articles);
    }

    #[Route(path: '/api/v1/articles/{id}', name: 'article', requirements: ['id' => '\d+'], methods: ['GET'])]
    public function index_article(int $id): Response
    {
        $articles = $this->articleProxyService->getArticleById($id);

        return new JsonResponse($articles);
    }
}