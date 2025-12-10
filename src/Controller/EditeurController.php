<?php

namespace App\Controller;

use App\Entity\Editeur;
use App\Form\EditeurType;
use App\Menu\MenuBuilder;
use App\Repository\EditeurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

#[Route('/editeur')]
final class EditeurController extends AbstractController
{
    #[Route(name: 'app_editeur_index', methods: ['GET'])]
    public function index(EditeurRepository $editeurRepository, MenuBuilder $menuBuilder, LoggerInterface $logger): Response
    {
        $logger->info('Accès à la liste des éditeurs.');
        
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Éditeurs de jeux vidéo', ['route' => 'app_editeur_index']);
        return $this->render('editeur/index.html.twig', [
            'editeurs' => $editeurRepository->findAll(),
            'breadcrumb' => $breadcrumb,
        ]);
    }

    #[Route('/new', name: 'app_editeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MenuBuilder $menuBuilder, LoggerInterface $logger): Response
    {
        $logger->info('Affichage du formulaire de création d\'éditeur.');
        
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Éditeurs de jeux vidéo', ['route' => 'app_editeur_index']);
        $breadcrumb->addChild('Créer un nouvel éditeur');

        $editeur = new Editeur();
        $form = $this->createForm(EditeurType::class, $editeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($editeur);
            $entityManager->flush();
            
            $logger->notice('Nouvel éditeur créé.', ['editeur_id' => $editeur->getId(), 'nom' => $editeur->getNom()]); 

            return $this->redirectToRoute('app_editeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('editeur/new.html.twig', [
            'editeur' => $editeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_editeur_show', methods: ['GET'])]
    public function show(Editeur $editeur, MenuBuilder $menuBuilder, LoggerInterface $logger): Response
    {
        $logger->info('Consultation de l\'éditeur.', ['editeur_id' => $editeur->getId(), 'nom' => $editeur->getNom()]); 
        
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Éditeurs de jeux vidéo', ['route' => 'app_editeur_index']);
        $breadcrumb->addChild($editeur->getNom());
        return $this->render('editeur/show.html.twig', [
            'editeur' => $editeur,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_editeur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Editeur $editeur, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $logger->info('Accès à la modification de l\'éditeur.', ['editeur_id' => $editeur->getId()]);
        
        $form = $this->createForm(EditeurType::class, $editeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            $logger->notice('Éditeur mis à jour.', ['editeur_id' => $editeur->getId(), 'nom' => $editeur->getNom()]); 

            return $this->redirectToRoute('app_editeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('editeur/edit.html.twig', [
            'editeur' => $editeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_editeur_delete', methods: ['POST'])]
    public function delete(Request $request, Editeur $editeur, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $id = $editeur->getId();
        $nom = $editeur->getNom();
        if ($this->isCsrfTokenValid('delete' . $editeur->getId(), $request->getPayload()->getString('_token'))) {
            try {
                $entityManager->remove($editeur);
                $entityManager->flush();
                $logger->warning('Éditeur supprimé (action irréversible).', ['editeur_id' => $id, 'nom' => $nom]);
            } catch (\Exception $e) {
                $logger->error('Erreur lors de la suppression de l\'éditeur.', ['editeur_id' => $id, 'error' => $e->getMessage()]);
            }
        } else {
             $logger->error('Tentative de suppression d\'éditeur avec token CSRF invalide.', ['editeur_id' => $id, 'csrf_ok' => false]); 
        }

        return $this->redirectToRoute('app_editeur_index', [], Response::HTTP_SEE_OTHER);
    }
}