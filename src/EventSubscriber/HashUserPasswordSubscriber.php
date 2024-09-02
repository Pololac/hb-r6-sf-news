<?php

namespace App\EventSubscriber;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class HashUserPasswordSubscriber implements EventSubscriberInterface
{

    public function __construct(
        private UserPasswordHasherInterface $hasher
    ){

    }

    public function getSubscribedEvents(): array    // Déclaration évènements auxquels on souscrit
    {
        return [
            Events::prePersist
        ];
    }

    public function prePersist(PrePersistEventArgs $args): Void
    {
        $entity = $args->getObject();

        if (!$entity instanceof User){     //Type-guard pour n'appliquer le hashage que si "User"
            return;
        }

        //Hashage du mot de passe
        $entity->setPassword(
            $this->hasher->hashPassword($entity, $entity->getPassword())
        );
    }


}