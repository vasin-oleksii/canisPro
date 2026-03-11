<?php

namespace App\DataFixtures;

use App\Entity\Cour;
use App\Entity\Type;
use App\Entity\Niveau;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CourFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $typeRepo = $manager->getRepository(Type::class);
        $niveauRepo = $manager->getRepository(Niveau::class);

        // Tableau de cours à créer : nom, prix, description, type, niveau
        $coursData = [
            ['Test1', 10.4, 'Ceci est la description du Test1', 'individuels', 'chiot'],
            ['Test2', 20.99, 'Ceci est la description du Test2', 'collectifs', 'débutant'],
            ['Test3', 0.99, 'Ceci est la description du Test3', 'collectifs', 'confirmé'],
        ];

        foreach ($coursData as $data) {
            [$nom, $prix, $description, $libelleType, $libelleNiveau] = $data;

            // Récupérer ou créer le type
            $type = $typeRepo->findOneBy(['libelleType' => $libelleType]);
            if (!$type) {
                $type = new Type();
                $type->setLibelleType($libelleType);
                $manager->persist($type);
            }

            // Récupérer ou créer le niveau
            $niveau = $niveauRepo->findOneBy(['libelleNiveau' => $libelleNiveau]);
            if (!$niveau) {
                $niveau = new Niveau();
                $niveau->setLibelleNiveau($libelleNiveau);
                $manager->persist($niveau);
            }

            // Créer le cours
            $cour = new Cour();
            $cour->setNomCour($nom)
                 ->setDescription($description)
                 ->setPrix($prix)
                 ->setType($type)
                 ->setNiveau($niveau);

            $manager->persist($cour);
        }

        $manager->flush();
    }
}