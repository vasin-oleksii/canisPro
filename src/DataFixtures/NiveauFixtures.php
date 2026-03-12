<?php

namespace App\DataFixtures;

use App\Entity\Niveau;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class NiveauFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $niveau1 = new Niveau();
        $niveau1->setLibelleNiveau("chiot");
        $manager->persist($niveau1);

        $niveau2 = new Niveau();
        $niveau2->setLibelleNiveau("débutant");
        $manager->persist($niveau2);

        $niveau3 = new Niveau();
        $niveau3->setLibelleNiveau("confirmé");
        $manager->persist($niveau3);

        $manager->flush();
    }
}
