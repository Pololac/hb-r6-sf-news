<?php

namespace App\EventSubscriber;

use App\Event\NewsletterRegisteredEvent;
use App\Newsletter\MailConfirmation;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewsletterRegisteredSubscriber implements EventSubscriberInterface
{
    public function __construct(
        private MailConfirmation $confirmation
    ){
    }

    public function sendConfirmationEmail(NewsletterRegisteredEvent $event): void
    {
        $this->confirmation->send($event->getNewsletterEmail());
    }

    public static function getSubscribedEvents(): array
    {
        return [
            NewsletterRegisteredEvent::NAME => 'sendConfirmationEmail',
        ];
    }
}
