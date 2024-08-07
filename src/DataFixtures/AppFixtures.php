<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

use Faker\Factory;
use Faker\ORM\Doctrine\Populator;

class AppFixtures extends Fixture
{
    private const NB_ARTICLES = 50;
    private const CATEGORIES_NAMES = ['Sport', 'France', 'Politique', 'Économie', 'International'];

    public function load(ObjectManager $manager): void
    {
        

    //  V1         for ($i = 0; $i < self::NB_ARTICLES; $i++) {
    //             $article = new Article();
    //             $article
    //                 ->setTitle("Article $i")             // Principe de l'interface fluide
    //                 ->setContent("Moncontenu $i")
    //                 ->setCreatedAt(new DateTime())
    //                 ->setVisible(true);

    //             }
    //             $manager->persist($article);
    //             $manager->flush();
    //      }
    
    //V2a
        $faker = Factory::create('fr_FR');

        $categories = [];

        // --CATEGORIES-----------------------------
        foreach (self::CATEGORIES_NAMES as $categoryName) {
            $category = new Category();
            $category->setName($categoryName);

            $manager->persist($category);
            $categories[] = $category;   
        }

        // --ARTICLES-----------------------------
        for ($i = 0; $i < self::NB_ARTICLES; $i++) {
            $article = new Article();
            $article
                ->setTitle($faker->realTextBetween(18,45))
                ->setContent($faker->realTextBetween(350,1500))
                ->setCreatedAt($faker->dateTimeBetween('-4 years'))
                ->setVisible($faker->boolean(80))      // 80 % chances d'être "true"*
                ->setCategory($faker->randomElement($categories));

        $manager->persist($article);
        }

        $manager->flush();

    //V2b
        // $generator = Factory::create('fr_FR');
        // $populator = new Populator($generator, $manager);

        // $populator->addEntity(Article::class, 20, [
        //     'updatedAt' => null,
        //     'category'=> $tableCategory,
        // ]);
        
        // $insertedPKs = $populator->execute();       


    }
}


