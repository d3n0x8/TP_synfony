<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use App\Menu\MenuBuilder;
use App\Repository\GenreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Psr\Log\LoggerInterface;

#[Route('/genre')]
final class GenreController extends AbstractController
{
    #[Route(name: 'app_genre_index', methods: ['GET'])]
    public function index(GenreRepository $genreRepository, MenuBuilder $menuBuilder, LoggerInterface $logger): Response 
    {
        $logger->info('Accès à la liste des genres.'); 
        
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Genre de jeux vidéo', ['route' => 'app_genre_index']);
        return $this->render('genre/index.html.twig', [
            'genres' => $genreRepository->findAll(),
            'breadcrumb' => $breadcrumb,
        ]);
    }

    #[Route('/new', name: 'app_genre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MenuBuilder $menuBuilder, LoggerInterface $logger): Response 
    {
        $logger->info('Affichage du formulaire de création de genre.'); 
        
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Genre de jeux vidéo', ['route' => 'app_genre_index']);
        $breadcrumb->addChild('Créer un nouveau genre');

        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($genre);
            $entityManager->flush();
            
            $logger->notice('Nouveau genre créé avec succès.', ['genre_id' => $genre->getId(), 'genre_nom' => $genre->getNom()]); 

            return $this->redirectToRoute('app_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('genre/new.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_genre_show', methods: ['GET'])]
    public function show(Genre $genre, MenuBuilder $menuBuilder, LoggerInterface $logger): Response 
    {
        $logger->info('Consultation du genre.', ['genre_id' => $genre->getId(), 'genre_nom' => $genre->getNom()]); 
        
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Genre de jeux vidéo', ['route' => 'app_genre_index']);
        $breadcrumb->addChild($genre->getNom());
        return $this->render('genre/show.html.twig', [
            'genre' => $genre,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_genre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Genre $genre, EntityManagerInterface $entityManager, LoggerInterface $logger): Response 
    {
        $logger->info('Accès à la modification du genre.', ['genre_id' => $genre->getId()]); 
        
        $form = $this->createForm(GenreType::class, $genre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            
            $logger->notice('Genre mis à jour avec succès.', ['genre_id' => $genre->getId(), 'genre_nom' => $genre->getNom()]); 

            return $this->redirectToRoute('app_genre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('genre/edit.html.twig', [
            'genre' => $genre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_genre_delete', methods: ['POST'])]
    public function delete(Request $request, Genre $genre, EntityManagerInterface $entityManager, LoggerInterface $logger): Response 
    {
        $id = $genre->getId();
        if ($this->isCsrfTokenValid('delete' . $genre->getId(), $request->getPayload()->getString('_token'))) {
            try {
                $entityManager->remove($genre);
                $entityManager->flush();
                $logger->warning('Genre supprimé (action irréversible).', ['genre_id' => $id, 'csrf_ok' => true]); 
            } catch (\Exception $e) {
                $logger->error('Erreur lors de la suppression du genre.', ['genre_id' => $id, 'error' => $e->getMessage()]); 
            }
        } else {
             $logger->error('Tentative de suppression de genre avec token CSRF invalide.', ['genre_id' => $id, 'csrf_ok' => false]);
        }

        return $this->redirectToRoute('app_genre_index', [], Response::HTTP_SEE_OTHER);
    }
}