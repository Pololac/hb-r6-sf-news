<?php

namespace App\Controller;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class ArticleController extends AbstractController
{
    #[Route('/articles', name: 'articles_list')]
    public function list(ArticleRepository $articleRepository): Response       // Injection de dépendance dans la méthode du Contrôleur : on lui dit que l'on a besoin d'un Repo d'Article ("type hinting") et derrière le contrôleur instancie lui-même la classe
    {
        $articles = $articleRepository->findAll();
        
        return $this->render('article/list.html.twig', [
            'articles' => $articles,
        ]);

        // return $this->render('article/list.html.twig', [     // Autre façon de l'écrire
        //     'articles' => $articleRepository->findAll(),
        // ]);

    }

    //---CLASSE REQUEST------
    // #[Route('/article', name: 'article_item')]
    // public function item(Request $request, ArticleRepository $articleRepository): Response  // Type-hinting de "Request"
    // {
    //     $id = $request->query->getInt('id');
    //     $article = $articleRepository->find($id);

    //     return $this->render('article/item.html.twig', [
    //         'article' => $article,
    //     ]);
    // }

    // #[Route('/article/{id}', name: 'article_item')]
    // public function item(ArticleRepository $articleRepository, int $id = 0): Response  // Type-hinting de "Request"
    // {
    //     $article = $articleRepository->find($id);

    //     if ($article === null) {                // "Throw early pattern"
    //         throw new NotFoundHttpException('Article introuvable');
    //     }

    //     return $this->render('article/item.html.twig', [
    //             'article' => $article,
    //     ]);

    // }

    #[Route('/article/{id}', name: 'article_item')]
    public function item(Article $article): Response
    {        
         return $this->render('article/item.html.twig', [
                'article' => $article,
        ]);     // ERREUR 404 générée automatiquement

    }



}
