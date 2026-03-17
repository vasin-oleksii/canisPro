<?php

namespace App\Controller;

use App\Repository\ProprietaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;


use App\Entity\Proprietaire;
use App\Form\ProprietaireType;
use Doctrine\ORM\EntityManagerInterface;


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
    
    #[Route('/membre/{id}', name: 'app_membre_modifier',requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function modifier(ProprietaireRepository $repository, int $id, EntityManagerInterface $manager, Request $request): Response
    {
        $membre = $repository->find($id);

        if (!$membre) {
            throw $this->createNotFoundException('Aucun membre trouvé pour cet utilisateur.');
        }

        $form = $this->createForm(ProprietaireType::class, $membre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $manager->persist($membre);
            $manager->flush();

            $this->addFlash('success', 'Membre modifié.');

            return $this->redirectToRoute('app_membre');
        }

        return $this->render('membre/modifMembre.html.twig', [
            'form' => $form->createView(),
            'membre' => $membre,
        ]);
    }
}