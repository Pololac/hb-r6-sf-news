<?php

namespace App\Controller;


use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{   //Une fonction par page
    
    #[Route('/', name: 'homepage')]     // Attribut PHP8 : à mettre au-dessus de la f° concernée
    public function index(): Response       // C'est le framework Symfony qui appelle les fonctions
    {
        return $this->render('index/index.html.twig', [
            'user_name' => 'Polo',
        ]);
    }
    
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('index/about.html.twig', []);
    }
}
