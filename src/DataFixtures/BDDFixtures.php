<?php

namespace App\DataFixtures;

use App\Entity\Type;
use App\Entity\Niveau;
use App\Entity\Cour;
use App\Entity\Seance;
use App\Entity\User;
use \DateTime;
use \DateInterval;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class BDDFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        //Manager pour chaque class
        $typeRepo = $manager->getRepository(Type::class);
        $niveauRepo = $manager->getRepository(Niveau::class);
        $courRepo = $manager->getRepository(Cour::class);

        //Tableau de données pour les Types
        $typeData = ["individuels", "collectifs"];

        foreach ($typeData as $data) {
            $type= new Type();
            $type->setLibelleType($data);
            $manager->persist($type);
        }

        //Tableau de données pour les Niveaux
        $niveauxData = ["chiot", "débutant", "confirmé"];

        foreach ($niveauxData as $data) {
            $niveau = new Niveau();
            $niveau->setLibelleNiveau($data);
            $manager->persist($niveau);
        }

        $manager->flush();

        //Tableau de données pour les Cours
        $coursData = [
            ['Test1', 10.4, 'Ceci est la description du Test1', 'individuels', 'chiot'],
            ['Test2', 20.99, 'Ceci est la description du Test2', 'collectifs', 'débutant'],
            ['Test3', 0.99, 'Ceci est la description du Test3', 'collectifs', 'confirmé'],
        ];

        foreach ($coursData as $data) {
            [$nom, $prix, $description, $libelleType, $libelleNiveau] = $data;

            //Verification de présence dans BDD
            $type = $typeRepo->findOneBy(['libelleType' => $libelleType]);
            $niveau = $niveauRepo->findOneBy(['libelleNiveau' => $libelleNiveau]);

            $cour = new Cour();
            $cour->setNomCour($nom)
                 ->setDescription($description)
                 ->setPrix($prix)
                 ->setType($type)
                 ->setNiveau($niveau);
            $manager->persist($cour);
        }

        $manager->flush();

        //Tableau de données pour les Seances
        $seancesData = [
            [new \DateTime('2008-09-08'), new \DateTime('14:00'), new \DateInterval('PT1H00M'), 'Test1'],
            [new \DateTime('2008-09-10'), new \DateTime('19:00'), new \DateInterval('PT1H30M'), 'Test1'],
            [new \DateTime('2008-09-12'), new \DateTime('8:00'), new \DateInterval('PT2H30M'), 'Test2'],
            [new \DateTime('2008-09-12'), new \DateTime('10:00'), new \DateInterval('PT0H30M'), 'Test2'],
            [new \DateTime('2008-09-15'), new \DateTime('11:11'), new \DateInterval('PT1H45M'), 'Test3'],
        ];

        foreach ($seancesData as $data) {
            [$date, $heureDeb, $duree, $nomCour] = $data;

            //Verification de présence dans BDD
            $cour = $courRepo->findOneBy(['nomCour' => $nomCour]);

            // Convertir DateInterval en DateTime pour le champ duree
            $dateReference = new \DateTime('00:00');
            $dateReference->add($duree); 

            $seance = new Seance();
            $seance->setDate($date)
                 ->setHeureDeb($heureDeb)
                 ->setDuree($dateReference)
                 ->setCour($cour);
            $manager->persist($seance);
        }

        $manager->flush();

        //Tableau de données pour les Users
        $usersData = [
            ["test@gmail.com", '$2y$13$0GgK5KF1t7mB9FZPse4DvemJkumBntkBtj6VoDA4nWkx/d26mjmhy', ["ROLE_USER"]], //password : test123
            ["user@gmail.com", '$2y$13$eijWxR0MBJrqnruniWydN.oSW3MEIT5f6P7yWrNHjyKlyoyct35QO', ["ROLE_USER"]], //password : essai
            ["admin@gmail.com", '$2y$13$oWF.36zexwb8zm.1u3LRROZjUuV/itOaD0DQmjbEzIVgaVjqrlCUe', ["ROLE_ADMIN","ROLE_USER"]], //password : admin
        ];

        foreach ($usersData as $data) {
            [$login, $pass, $role] = $data;

            $user = new User();
            $user->setEmail($login)
                 ->setPassword($pass)
                 ->setRoles($role);
            $manager->persist($user);
        }

        $manager->flush();
    }
}
