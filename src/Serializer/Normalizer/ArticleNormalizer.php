<?php

namespace App\Serializer\Normalizer;

use App\Entity\Article;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class ArticleNormalizer implements NormalizerInterface
{
    public function __construct(
        #[Autowire(service: 'serializer.normalizer.object')]
        private NormalizerInterface $normalizer,
        private UrlGeneratorInterface $router   // car impossible de type-hinter directement la function "normalize"
    ) {
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        $data = $this->normalizer->normalize($object, $format, $context);

        // TODO: add, edit, or delete some data
        $data['url'] = $this->router->generate(
            "article_item",     //"Path" qui donne Uri
            ['id' => $object->getId()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        return $data;
    }

    public function supportsNormalization($data, string $format = null, array $context = []): bool
    {
        return $data instanceof Article && array_search('categories_read', $context['groups'])===false;
    }       // Utilise le normalizer uniquement si Article et que le "groups" n'est pas "categories_read"

    public function getSupportedTypes(?string $format): array
    {
        return [Article::class => true];
    }
}
