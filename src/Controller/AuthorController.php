<?php

declare(strict_types=1);

namespace App\Controller;

use App\Infastructure\JsonResponse;
use App\Service\Author\AuthorProxyServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AuthorController extends AbstractController
{
    public function __construct(
        private readonly AuthorProxyServiceInterface $authorProxyService,
    ) {
    }

    #[Route(path: '/api/v1/authors', name: 'authors_list')]
    public function index_list(): Response
    {
        $authors = $this->authorProxyService->getAuthorsList();

        return new JsonResponse($authors);
    }
}