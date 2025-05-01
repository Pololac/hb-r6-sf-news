<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class IsNotSpamValidator extends ConstraintValidator
{

    public function __construct(
        private HttpClientInterface $spamChecker
    ){}


    public function validate(mixed $value, Constraint $constraint): void
    {
        $response = $this->spamChecker->request(   //Envoi de l'email rentré à l'API SpamChecker
            Request::METHOD_POST, // On utilise la méthode POST
            "/api/check", // la fin de l'URL que nous souhaitons requêter (base uri définie ds env.local)
            [ // La donnée sera automatiquement convertie au format JSON et intégrée au corps de la requête
              'json' => ['email' => $value]
            ]
          );

        $data = $response->toArray();
        // dd($data);
        $isSpam = $data['result'] === 'spam';

        if (!$isSpam){
            return;
        }

        // Ajoute une erreur au formulaire avec le message programmé dans le fichierIsNotSpam.php
        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
