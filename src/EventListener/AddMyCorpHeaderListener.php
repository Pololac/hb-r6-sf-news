<?php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class AddMyCorpHeaderListener
{
    // #[AsEventListener]
    public function addHeader(ResponseEvent $event): void
    {
        $response = $event->getResponse();

        $response->headers->add([
            'X-DEVELOPED-BY' => 'HB-R6-LA-DREAM-TEAM'
        ]);
    }
}