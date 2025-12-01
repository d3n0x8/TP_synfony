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

#[Route('/jeu/video')]
final class JeuVideoController extends AbstractController
{
    #[Route(name: 'app_jeu_video_index', methods: ['GET'])]
    public function index(JeuVideoRepository $jeuVideoRepository, MenuBuilder $menuBuilder): Response
    {
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Jeux Vidéo', ['route' => 'app_jeu_video_index']);
        return $this->render('jeu_video/index.html.twig', [
            'jeu_videos' => $jeuVideoRepository->findAll(),
            'breadcrumb' => $breadcrumb,
        ]);
    }

    #[Route('/new', name: 'app_jeu_video_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MenuBuilder $menuBuilder): Response
    {
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Jeux Vidéo', ['route' => 'app_jeu_video_index']);
        $breadcrumb->addChild('Créer un nouveau jeu vidéo');

        $jeuVideo = new JeuVideo();
        $form = $this->createForm(JeuVideoType::class, $jeuVideo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($jeuVideo);
            $entityManager->flush();

            return $this->redirectToRoute('app_jeu_video_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('jeu_video/new.html.twig', [
            'jeu_video' => $jeuVideo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jeu_video_show', methods: ['GET'])]
    public function show(JeuVideo $jeuVideo, MenuBuilder $menuBuilder): Response
    {
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Jeux Vidéo', ['route' => 'app_jeu_video_index']);
        $breadcrumb->addChild($jeuVideo->getTitre());

        return $this->render('jeu_video/show.html.twig', [
            'jeu_video' => $jeuVideo,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_jeu_video_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, JeuVideo $jeuVideo, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(JeuVideoType::class, $jeuVideo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_jeu_video_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('jeu_video/edit.html.twig', [
            'jeu_video' => $jeuVideo,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_jeu_video_delete', methods: ['POST'])]
    public function delete(Request $request, JeuVideo $jeuVideo, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $jeuVideo->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($jeuVideo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_jeu_video_index', [], Response::HTTP_SEE_OTHER);
    }
}
