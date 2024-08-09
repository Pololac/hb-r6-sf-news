<?php

namespace App\Controller;


use App\Entity\NewsletterEmail;
use App\Form\NewsletterEmailType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IndexController extends AbstractController
{   //Une fonction par page
    
    #[Route('/', name: 'homepage')]
    public function home(CategoryRepository $categoryRepository): Response
    {
        // 1 - Je requête le modèle (SQL/BDD)
        // pour récupérer les catégories
        $categories = $categoryRepository->findAll();

        // 2 - Je demande à Twig de rendre une vue
        // et je lui passe les catégories
        // Répertoire racine des vues : templates/
        return $this->render('index/index.html.twig', [
            'categories' => $categories,
        ]);
    }
    
    #[Route('/about', name: 'about')]
    public function about(): Response
    {
        return $this->render('index/about.html.twig', []);
    }

    #[Route('/newsletter/subscribe', name: 'newsletter_subscribe', methods: ['GET', 'POST'])]
    public function newsletterSubscribe(
        Request $request,
        EntityManagerInterface $em
        ): Response
    {
        $newsletter = new NewsletterEmail();
        $form = $this->createForm(NewsletterEmailType::class, $newsletter);
        
        // Prends en charge la requête entrante et s'il y a des données POST, les met dans $newsletter
        $form->handleRequest($request);

        // Enregistrement de mon email
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($newsletter);
            $em->persist($newsletter);
            $em->flush();

            return $this->redirectToRoute('newsletter_confirm');
        }    

        return $this->render('index/newsletter.html.twig', [
            'newsletterForm' => $form
        ]);
    }

    #[Route('/newsletter/thanks', name: "newsletter_confirm")]
    public function newsletterConfirm() : Response{
        return $this->render('index/newsletter_confirm.html.twig');
    }
}
