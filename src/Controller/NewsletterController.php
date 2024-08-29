<?php

namespace App\Controller;

use App\Entity\NewsletterEmail;
use App\Form\NewsletterEmailType;
use App\Newsletter\MailConfirmation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

class NewsletterController extends AbstractController
{
    #[Route('/newsletter/subscribe', name: 'newsletter_subscribe', methods: ['GET', 'POST'])]
    public function newsletterSubscribe(
        Request $request,
        EntityManagerInterface $em,    // Communication avec BDD
        MailConfirmation $mailconfirmation   // Pour utiliser notre service "MailConfirmation"
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

            $mailconfirmation->send($newsletter);

            return $this->redirectToRoute('newsletter_confirm');
        }    

        //Affiche formulaire si rien dans POST
        return $this->render('newsletter/newsletter.html.twig', [
            'newsletterForm' => $form
        ]);
    }

    #[Route('/newsletter/thanks', name: "newsletter_confirm")]
    public function newsletterConfirm() : Response{
        return $this->render('newsletter/newsletter_confirm.html.twig');
    }
}
