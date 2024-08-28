<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Normalizer\DateTimeNormalizer;

#[Route('/api')]
class ApiController extends AbstractController
{
    #[Route('/articles', name: 'api_articles_list', methods: ['GET'])]
    public function articlesList(ArticleRepository $articleRepository): Response
    {
        $articles = $articleRepository->findAll();
        
        return $this->json($articles, context:[         //F° "json" pour serializer
            'groups' => ['articles_read'],              // Pour lire articles, on ne serialize que les propriétés définies dans l'entité via l'attribut "Groups"
            DateTimeNormalizer::FORMAT_KEY => 'd/m/Y'   // Changement format de la date
        ]);
    }

    #[Route('/categories', name: 'api_categories_list', methods: ['GET'])]
    public function categoriesList(CategoryRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->json($categories, context:[         //F° "json" pour serializer
            'groups' => ['categories_read']
        ]);
    }
}
