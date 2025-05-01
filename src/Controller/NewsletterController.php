<?php

namespace App\Controller;

use App\Entity\NewsletterEmail;
use App\Event\NewsletterRegisteredEvent;
use App\Form\NewsletterEmailType;
use App\Newsletter\MailConfirmation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class NewsletterController extends AbstractController
{
    #[Route('/newsletter/subscribe', name: 'newsletter_subscribe', methods: ['GET', 'POST'])]
    public function newsletterSubscribe(
        Request $request,
        EntityManagerInterface $em,    // Communication avec BDD
        // MailConfirmation $mailconfirmation   // Pour utiliser notre service "MailConfirmation"
        EventDispatcherInterface $dispatcher,
        HttpClientInterface $spamChecker        //Pour interroger l'API SpamChecker, en utilisant le nom défini dans framework.yaml pour la "base uri"
        ): Response
    {
        $newsletter = new NewsletterEmail();
        $form = $this->createForm(NewsletterEmailType::class, $newsletter);     //Crée le formulaire en utilisant le modèle défini dans NewsletterEmailType
        
        // Prends en charge la requête entrante et s'il y a des données POST, les met dans $newsletter
        $form->handleRequest($request);

        // Enregistrement de mon email
        if ($form->isSubmitted() && $form->isValid()){  //"isValid" fait référence aux Validator/Constraints de l'entité NewsletterEmail
            $em->persist($newsletter);
            $em->flush();

        //Diffusion de l'event "NAME" aux autres services
            $dispatcher->dispatch(
                new NewsletterRegisteredEvent($newsletter),
                NewsletterRegisteredEvent::NAME
                );

            return $this->redirectToRoute('newsletter_confirm');
        }

            // $form
            //     ->get('email')
            //     ->addError(new FormError("Une erreur est survenue lors de la vérification de l'email"));    //on applique addError sur le formulaire "fils" EmailType : relié au champs "addEmail"

            // $mailconfirmation->send($newsletter);
  

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
