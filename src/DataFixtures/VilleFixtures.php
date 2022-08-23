<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Ville;

class VilleFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // for($i = 1; $i <= 10; $i++){
        //     $ville = new Ville();
        //     $ville->setNom("Ville n°$i");
        //     $manager->persist($ville);
        // }

        $manager->flush();
    }
}
