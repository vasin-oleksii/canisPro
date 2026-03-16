<?php

namespace App\Controller;

use App\Repository\CourRepository;
use App\Repository\SeanceRepository;
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
    public function listeCours(CourRepository $repository): Response
    {
        $cours = $repository->findAll();
        return $this->render('accueil/listeCours.html.twig', ['cours' => $cours]);
    }

    #[Route('/details-cour-{id}', name: 'app_details_cour')]
    public function detailsCour(CourRepository $repository, int $id): Response
    {
        $cour = $repository->find($id);
        return $this->render('accueil/detailsCour.html.twig', ['cour' => $cour]);
    }

    #[Route('/liste-des-seances', name: 'app_liste_seances')]
    public function listeSeances(SeanceRepository $repository): Response
    {
        $seances = $repository->findAll();
        return $this->render('accueil/listeSeances.html.twig', ['seances' => $seances]);
    }

    #[Route('/details-seances-{id}', name: 'app_details_seance')]
    public function seance(int $id, SeanceRepository $repository): Response
    {
        $seance = $repository->find($id);
        return $this->render('accueil/detailsSeance.html.twig', ['seance' => $seance]);
    }

    #[Route('/a-propos', name: 'app_a_propos')]
    public function aPropos(): Response
    {
        return $this->render('accueil/aPropos.html.twig');
    }
}
