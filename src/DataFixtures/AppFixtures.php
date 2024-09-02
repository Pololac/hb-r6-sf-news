<?php

namespace App\DataFixtures;

use App\Entity\Article;
use App\Entity\Category;
use App\Entity\User;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\ORM\Doctrine\Populator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private const NB_ARTICLES = 50;
    private const CATEGORIES_NAMES = ['Sport', 'France', 'Politique', 'Économie', 'International'];

    // public function __construct(
    //     private UserPasswordHasherInterface $hasher
    // ){}
        //Plus nécessaire car effectuer directement dans les EventSubscribers

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
        // $faker = Factory::create('fr_FR');

        // $categoryNameIdx = 0;

        // $populator = new Populator($faker, $manager);
        // $populator->addEntity(Category::class, count(self::CATEGORIES_NAMES), [
        //     'name' => function () use (&$categoryNameIdx) {      // "&" pr passage par référence
        //         return self::CATEGORIES_NAMES[$categoryNameIdx++];
        //     }
        // ]);
        // $populator->addEntity(Article::class, self::NB_ARTICLES, [
        //     'title' => function () use ($faker) {
        //         return $faker->realTextBetween(9, 15);
        //     },
        //     'content' => function () use ($faker) {
        //         return $faker->realTextBetween(150, 350);
        //     },
        //     'updatedAt' => null
        // ]);

        // $populator->execute();

        // --USERS-----------------------------
        $admin = new User();
        $admin
            ->setEmail("admin@test.com")
            ->setRoles(["ROLE_ADMIN"])
            ->setPassword("admin1234");
            // ->setPassword($this -> hasher ->hashPassword($admin, "admin1234"));  //RMQ : Possibilité de changer le logiciel de hashage en fonction des users
                //Pas nécessaire car effectuer directement dans les EventSubscribers
        $manager->persist($admin);

        $user = new User();
        $user
            ->setEmail("user@test.com")
            ->setPassword("user1234");
            // ->setPassword($this -> hasher ->hashPassword($user, "user1234"));  //Possibilité de changer le logiciel de hashage en fonction des users
                //Pas nécessaire car effectuer directement dans les EventSubscribers

        $manager->persist($user);

        $manager->flush();

    }
}


