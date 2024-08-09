<?php

namespace App\Controller;

use App\Entity\NewsletterEmail;
use App\Form\NewsletterEmailType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class NewsletterController extends AbstractController
{
    #[Route('/newsletter/subscribe', name: 'newsletter_subscribe', methods: ['GET', 'POST'])]
    public function newsletterSubscribe(
        Request $request,
        EntityManagerInterface $em
        ): Response
    {
        $newsletter = new NewsletterEmail();
        $form = $this->createForm(NewsletterEmailType::class, $newsletter);     //Crée le formulaire en utilisant le modèle défini dans NewsletterEmailType
        
        // Prends en charge la requête entrante et s'il y a des données POST, les met dans $newsletter
        $form->handleRequest($request);

        // Enregistrement de mon email
        if ($form->isSubmitted() && $form->isValid()) {
            // dd($newsletter);
            $em->persist($newsletter);
            $em->flush();

            return $this->redirectToRoute('newsletter_confirm');
        }    

        //Affiche formulaire si rien dans POST
        return $this->render('index/newsletter.html.twig', [
            'newsletterForm' => $form
        ]);
    }

    #[Route('/newsletter/thanks', name: "newsletter_confirm")]
    public function newsletterConfirm() : Response{
        return $this->render('index/newsletter_confirm.html.twig');
    }
}
