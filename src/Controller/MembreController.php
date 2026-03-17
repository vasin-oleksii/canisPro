<?php

namespace App\Controller;

use App\Repository\ProprietaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ChienType;

use App\Entity\Chien;
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
    
    #[Route('/membre/chien-ajout', name: 'app_chien_ajout', methods:['GET','POST'])]
    #[Route('/membre/chien-modification-{id}', name: 'app_chien_modification', requirements:['id'=>'\d+'], methods:['GET','POST'])]
    public function Ajout_et_Modif(#[MapEntity] ?Chien $chien, ProprietaireRepository $repository, Request $request, EntityManagerInterface $manager): Response
    {
        $isModification = $chien !== null;
        if(!$chien){
            $chien = new Chien();
        }
        $form = $this->createForm(ChienType::class, $chien);
        $form->handleRequest($request);

        $membre = $repository->findOneBy(['user' => $this->getUser()]);

        if ($form->isSubmitted() && $form->isValid()) {

            $chien->setProprietaire($membre);
            $manager->persist($chien);
            $manager->flush();

            if ($isModification) {
                $this->addFlash("success", "Modification effectuée");
            } else {
                $this->addFlash("success", "Ajout effectuée");
            }

            return $this->redirectToRoute('app_membre');
        }

        return $this->render('membre/formulaireChien.html.twig', ['chien' => $chien, 'form' => $form->createView(), 'membre' => $membre, 'isModif' => $isModification]);
    }

    #[Route('/membre/chien/suprimer/{id}', name:'app_chien_suppression', methods:['POST'])]
    public function supprimer(#[MapEntity] ?Chien $chien, Request $request, EntityManagerInterface $manager): Response
    {
        if ($this->isCsrfTokenValid("sup" . $chien->getId(), $request->request->get('_token'))) {
            $manager->remove($chien);
            $manager->flush();
            $this->addFlash("success", "Suppression effectuée");
        }
        
        return $this->redirectToRoute('app_membre');
    }
}
