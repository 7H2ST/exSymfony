<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Restaurant;
use App\Entity\Ville;
use App\Entity\Proprietaire;

class RestaurantFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // for($i = 1; $i <= 5; $i++){
        //     $restaurant = new Restaurant();
        //     $restaurant->setNom("Restaurant n°$i")
        //                 ->setAdresse("Adresse du restaurant n°$i")
        //                 ->setImage("http://placehold.it/350x350");
        //     $manager->persist($restaurant);
        // }
        $faker = \Faker\Factory::create('fr_FR');
        
        for($i = 1; $i <= 8; $i++){
            $ville = new Ville();
            $ville->setNom($faker->city());

            $manager->persist($ville);

            for($j = 1; $j <= mt_rand(2,4); $j++){


                $proprietaire = new Proprietaire();
                $proprietaire->setNom($faker->lastName())
                            ->setPrenom($faker->firstName())
                            ->setDateNaissance($faker->dateTimeInInterval('-60 years', '+25 years'))
                            ->setUsername($faker->numerify('user-####'))
                            ->setPassword($faker->randomNumber(4, true));
                $manager->persist($proprietaire);

                $restaurant = new Restaurant();
                $restaurant->setNom($faker->company())
                            ->setAdresse($faker->streetAddress())
                            ->setImage($faker->imageUrl())
                            ->setVille($ville)
                            ->setProprietaire($proprietaire);
                $manager->persist($restaurant);
            }
        }

        $manager->flush();
    }
}
