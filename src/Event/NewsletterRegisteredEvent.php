<?php

namespace App\Event;
use App\Entity\NewsletterEmail;

class NewsletterRegisteredEvent
{
    public const NAME = 'newsletter.registered';

    public function __construct(
        private NewsletterEmail $newsletterEmail
        ){
    }

    public function getNewsletterEmail(): NewsletterEmail
    {
        return $this->newsletterEmail;
    }
}