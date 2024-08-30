<?php

namespace App\Security\Voter;

use App\Entity\Article;
use App\Entity\User;
use DateInterval;
use DateTime;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class ArticleVoter extends Voter
{
    public const EDIT = 'ARTICLE_EDIT';

    public function __construct(
        private Security $security
    ) {
    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::EDIT && $subject instanceof Article;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User || !$subject instanceof Article) {
            return false;
        }

        $createdAt = $subject->getCreatedAt();
        $articleYear = intval($createdAt->format('Y'));
        $now = new DateTime();
        $currentYear = intval($now->format('Y'));

        // - Si on demande une édition
        // - Si on est admin
        // - Et si l'année de l'article est supérieure ou égale à N - 2
        if ($attribute === self::EDIT && $this->security->isGranted('ROLE_ADMIN') && $articleYear >= $currentYear - 2) {
            return true;
        }

        return false;
    }
}