<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Proprietaire;

class ProprietaireFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // for($i = 1; $i <= 5; $i++){
        //     $proprietaire = new Proprietaire();
        //     $proprietaire->setNom("Nom propriétaire n°$i")
        //                 ->setPrenom("Prénom propriétaire n°$i")
        //                 ->setDateNaissance(new \DateTime());
        //     $manager->persist($proprietaire);
        // }

        $manager->flush();
    }
}
