<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AccueilController extends AbstractController
{
    #[Route('/', name: 'app_accueil')]
    public function index(): Response
    {
        return $this->render('accueil/index.html.twig');
    }

    #[Route('/liste-des-cours', name: 'app_liste_cours')]
    public function listeCours(): Response
    {
        return $this->render('accueil/listeCours.html.twig');
    }

    #[Route('/a-propos', name: 'app_a_propos')]
    public function aPropos(): Response
    {
        return $this->render('accueil/aPropos.html.twig');
    }
}
