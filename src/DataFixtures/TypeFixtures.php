<?php

namespace App\DataFixtures;

use App\Entity\Type;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TypeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $type1 = new Type();
        $type1->setLibelleType("individuels");
        $manager->persist($type1);

        $type2 = new Type();
        $type2->setLibelleType("collectifs");
        $manager->persist($type2);

        $manager->flush();
    }
}
