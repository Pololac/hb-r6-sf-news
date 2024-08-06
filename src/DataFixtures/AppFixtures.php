<?php

namespace App\DataFixtures;

use App\Entity\Article;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

class AppFixtures extends Fixture
{
    private const NB_ARTICLES = 150;

    public function load(ObjectManager $manager): void
    {

//         for ($i = 0; $i < self::NB_ARTICLES; $i++) {
//             $article = new Article();
//             $article
//                 ->setTitle("Article $i")
//                 ->setContent("Moncontenu $i")
//                 ->setCreatedAt(new DateTime())
//                 ->setVisible(true);

    // $faker = Factory::create('fr_FR');

    //     for ($i = 0; $i < self::NB_ARTICLES; $i++) {
    //         $article = new Article();
    //         $article
    //             ->setTitle($faker -> realTextBetween(18,45))
    //             ->setContent($faker->realTextBetween(350,1500))
    //             ->setCreatedAt($faker->dateTimeBetween('-4 years'))
    //             ->setVisible($faker->boolean(80));      // 80 % chances d'être "true"

    //     $manager->persist($article);

    //     }

    // $manager->flush();


            $generator = Factory::create('fr_FR');
            $populator = new Populator($generator, $manager);

            $populator->addEntity(Article::class, 20, [
                'updatedAt' => null
            ]);
            
            $insertedPKs = $populator->execute();       


    }
}


