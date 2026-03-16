<?php

namespace App\Controller;

use App\Repository\ProprietaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Entity\Proprietaire;

final class MembreController extends AbstractController
{
    #[Route('/membre', name: 'app_membre')]
    public function index(ProprietaireRepository $repository): Response
    {
        $membre = $repository->findOneBy(['user' => $this->getUser()]);

        if (!$membre) {
            throw $this->createNotFoundException('Aucun membre trouvé pour cet utilisateur.');
        }

        return $this->render('membre/index.html.twig',[
            'membre' => $membre,
        ]);
    }
}
