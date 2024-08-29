<?php

namespace App\Newsletter;

use App\Entity\NewsletterEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailConfirmation
{
    public function __construct(
        private MailerInterface $mailer,
        private string $adminEmail
    ){

    }

    public function send(NewsletterEmail $newsletterEmail)
    {
        $email = (new Email())
        ->from($this->adminEmail)
        ->to($newsletterEmail->getEmail())
        ->subject('HB News - Inscription à la newsletter')
        ->text('Votre email a bien été enregistré à notre newsletter')  //Pour permettre affichage si HTML désactivé
        ->html('<p>Votre email a bien été enregistré à notre newsletter</p>');

        $this->mailer->send($email);
    }
}