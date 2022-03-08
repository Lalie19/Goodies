<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\DBAL\Driver\IBMDB2\Exception\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $faker = Factory::create();

        // for ($i = 1; $i < 5; $i++) {
        //     $category = new Category;
        //     $category->setName($faker->words(3, true));

        //     $manager->persist($category);

        //     for ($j = 1; $j < 20; $j++) {
        //         $product = new Product;
        //         $product
        //             ->setName($faker->name())
        //             ->setDescription($faker->text())
        //             ->setPrice(mt_rand(100, 100000))
        //             ->setCategory($category);

        //         $manager->persist($product);
        //     }
        // }

        $admin = new User;
        $admin
            ->setEmail("admin@localhost.com")
            ->setFirstname("Lalie")
            ->setPassword('$2y$13$dJnPnUWXI6y0c0kwhUhxcudyXaoymS4vb4W4B/Wtu3BxvFro5KnFq') // admin1234
            ->setLastname ("PETIT")
            ->setAdress("adresse de l'entreprise")
            ->setRoles(["ROLE_ADMIN"]);
        $manager->persist($admin);



        $manager->flush();
        // $product = new Product();
        // $manager->persist($product);

        

        $manager->flush();
    }
}
