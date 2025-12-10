<?php

namespace App\Controller;

use App\Entity\JeuVideo;
use App\Form\JeuVideoType;
use App\Menu\MenuBuilder;
use App\Repository\JeuVideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

#[Route('/jeu/video')]
final class JeuVideoController extends AbstractController
{
    #[Route(name: 'app_jeu_video_index', methods: ['GET'])]
    public function index(JeuVideoRepository $jeuVideoRepository, MenuBuilder $menuBuilder, LoggerInterface $logger): Response
    {
        $logger->info('Accès à la liste des jeux vidéo.'); 
        
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Jeux Vidéo', ['route' => 'app_jeu_video_index']);
        return $this->render('jeu_video/index.html.twig', [
            'jeu_videos' => $jeuVideoRepository->findAll(),
            'breadcrumb' => $breadcrumb,
        ]);
    }

    #[Route('/new', name: 'app_jeu_video_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MenuBuilder $menuBuilder, LoggerInterface $logger): Response
    {
        $logger->info('Affichage du formulaire de création de jeu vidéo.'); 
        
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Jeux Vidéo', ['route' => 'app_jeu_video_index']);
        $breadcrumb->addChild('Créer un nouveau jeu vidéo');

        $jeuVideo = new JeuVideo();
        $form = $this->createForm(JeuVideoType::class, $jeuVideo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jeuVideo);
            $entityManager->flush();
            
            $logger->notice('Nouveau jeu vidéo créé.', ['jeu_id' => $jeuVideo->getId(), 'titre' => $jeuVideo->getTitre()]); 

            return $this->redirectToRoute('app_jeu_video_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('jeu_video/new.html.twig', [
            'jeu_video' => $jeuVideo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jeu_video_show', methods: ['GET'])]
    public function show(JeuVideo $jeuVideo, MenuBuilder $menuBuilder, LoggerInterface $logger): Response
    {
        $logger->info('Consultation du jeu vidéo.', ['jeu_id' => $jeuVideo->getId(), 'titre' => $jeuVideo->getTitre()]); 

        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Jeux Vidéo', ['route' => 'app_jeu_video_index']);
        $breadcrumb->addChild($jeuVideo->getTitre());

        return $this->render('jeu_video/show.html.twig', [
            'jeu_video' => $jeuVideo,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_jeu_video_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JeuVideo $jeuVideo, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $logger->info('Accès à la modification du jeu vidéo.', ['jeu_id' => $jeuVideo->getId()]); 
        
        $form = $this->createForm(JeuVideoType::class, $jeuVideo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            $logger->notice('Jeu vidéo mis à jour.', ['jeu_id' => $jeuVideo->getId(), 'titre' => $jeuVideo->getTitre()]); 

            return $this->redirectToRoute('app_jeu_video_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('jeu_video/edit.html.twig', [
            'jeu_video' => $jeuVideo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jeu_video_delete', methods: ['POST'])]
    public function delete(Request $request, JeuVideo $jeuVideo, EntityManagerInterface $entityManager, LoggerInterface $logger): Response
    {
        $id = $jeuVideo->getId();
        if ($this->isCsrfTokenValid('delete' . $jeuVideo->getId(), $request->getPayload()->getString('_token'))) {
            try {
                $entityManager->remove($jeuVideo);
                $entityManager->flush();
                $logger->warning('Jeu vidéo supprimé (action irréversible).', ['jeu_id' => $id, 'titre' => $jeuVideo->getTitre()]); 
            } catch (\Exception $e) {
                $logger->error('Erreur lors de la suppression du jeu vidéo.', ['jeu_id' => $id, 'error' => $e->getMessage()]); 
            }
        } else {
             $logger->error('Tentative de suppression de jeu vidéo avec token CSRF invalide.', ['jeu_id' => $id, 'csrf_ok' => false]);  
        }

        return $this->redirectToRoute('app_jeu_video_index', [], Response::HTTP_SEE_OTHER);
    }
}