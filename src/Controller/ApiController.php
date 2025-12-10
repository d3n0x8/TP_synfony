<?php

namespace App\Controller;

use App\Entity\Genre;
use App\Entity\JeuVideo;
use App\Entity\Utilisateur;
use App\Repository\GenreRepository;
use App\Repository\JeuVideoRepository;
use App\Repository\UtilisateurRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/api')]
final class ApiController extends AbstractController
{
    private $entityManager;
    private $logger;

    public function __construct(EntityManagerInterface $entityManager, LoggerInterface $logger)
    {
        $this->entityManager = $entityManager;
        $this->logger = $logger;
    }

    #[Route('/ping', name: 'api_ping', methods: ['GET'])]
    public function ping(): Response
    {
        $this->logger->info('API Ping called.', ['route' => 'api_ping']);
        return new Response('pong', Response::HTTP_OK); 
    }

    #[Route('/healthcheck', name: 'api_healthcheck', methods: ['GET'])]
    public function healthcheck(): JsonResponse
    {
        $dbStatus = 'OK';
        $message = 'API et Dépendances OK';
        $statusCode = Response::HTTP_OK;

        try {
            $this->entityManager->getConnection()->connect();
            if (!$this->entityManager->getConnection()->isConnected()) {
                $dbStatus = 'ERROR';
                $message = 'Erreur de connexion à la base de données.';
                $statusCode = Response::HTTP_SERVICE_UNAVAILABLE; 
            }
        } catch (\Exception $e) {
            $dbStatus = 'CRITICAL';
            $message = 'Erreur critique de la base de données: ' . $e->getMessage();
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR; 
            $this->logger->critical('Healthcheck BDD CRITICAL', ['error' => $e->getMessage()]);
        }
        
        $this->logger->info('API Healthcheck executed.', ['db_status' => $dbStatus]);

        return $this->json([
            'status' => $message,
            'database' => $dbStatus,
            'timestamp' => (new \DateTimeImmutable())->format('Y-m-d H:i:s'),
        ], $statusCode);
    }

    #[Route('/jeu_video', name: 'api_jeu_video_index', methods: ['GET'])]
    public function indexJeuVideo(JeuVideoRepository $jeuVideoRepository): JsonResponse
    {
        $this->logger->info('API: List all video games called.', ['route' => 'api_jeu_video_index']);
        $jeuVideos = $jeuVideoRepository->findAll();

        $data = array_map(fn(JeuVideo $jeuVideo) => [
            'id' => $jeuVideo->getId(),
            'titre' => $jeuVideo->getTitre(),
            'dateSortie' => $jeuVideo->getDateSortie()?->format('d/m/Y'),
            'description' => $jeuVideo->getDescription(),
            'prix' => number_format($jeuVideo->getPrix(), 2, ',', ' '),
            'imageUrl' => $jeuVideo->getImageUrl(),
            'editeur' => $jeuVideo->getEditeur()->getNom(),
            'genre' => $jeuVideo->getGenre()->getNom(),
            'genreId' => $jeuVideo->getGenre()->getId(),
            'developpeur' => $jeuVideo->getDeveloppeur(),
        ], $jeuVideos);

        return $this->json([
            'success' => true,
            'count' => count($data),
            'data' => $data,
        ]);
    }

    #[Route('/jeu_video/{id}', name: 'api_jeu_video_show', methods: ['GET'])]
    public function showJeuVideo(?JeuVideo $jeuVideo): JsonResponse
    {
        if (!$jeuVideo) {
            $this->logger->notice('API: Attempt to show non-existent video game.', ['id' => $jeuVideo->getId()]);
            return $this->json(['success' => false, 'error' => 'Jeu vidéo non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $this->logger->info('API: Show video game details.', ['id' => $jeuVideo->getId()]);

        return $this->json([
            'success' => true,
            'data' => [
                'id' => $jeuVideo->getId(),
                'titre' => $jeuVideo->getTitre(),
                'dateSortie' => $jeuVideo->getDateSortie()?->format('d/m/Y'),
                'description' => $jeuVideo->getDescription(),
                'prix' => number_format($jeuVideo->getPrix(), 2, ',', ' '),
                'imageUrl' => $jeuVideo->getImageUrl(),
                'editeur' => $jeuVideo->getEditeur()->getNom(),
                'genre' => $jeuVideo->getGenre()->getNom(),
                'developpeur' => $jeuVideo->getDeveloppeur(),
            ]
        ]);
    }

    #[Route('/genre', name: 'api_genre_index', methods: ['GET'])]
    public function indexGenre(GenreRepository $genreRepository): JsonResponse
    {
        $this->logger->info('API: List all genres called.', ['route' => 'api_genre_index']);
        $genres = $genreRepository->findAll();

        $data = array_map(fn(Genre $genre) => [
            'id' => $genre->getId(),
            'nom' => $genre->getNom(),
            'description' => $genre->getDescription(),
            'actif' => $genre->isActif() ?? false,
            'createdAt' => $genre->getCreatedAt()?->format('d/m/Y'),
            'updatedAt' => $genre->getUpdateAt()?->format('d/m/Y'),
        ], $genres);

        return $this->json(['success' => true, 'count' => count($data), 'data' => $data]);
    }

    #[Route('/genre/{id}', name: 'api_genre_show', methods: ['GET'])]
    public function showGenre(?Genre $genre): JsonResponse
    {
        if (!$genre) {
            $this->logger->notice('API: Attempt to show non-existent genre.', ['id' => $genre->getId()]);
            return $this->json(['success' => false, 'error' => 'Genre non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $this->logger->info('API: Show genre details.', ['id' => $genre->getId()]);
        return $this->json([
            'success' => true,
            'data' => [
                'id' => $genre->getId(),
                'nom' => $genre->getNom(),
                'description' => $genre->getDescription(),
                'actif' => $genre->isActif() ?? false,
                'createdAt' => $genre->getCreatedAt()?->format('d/m/Y'),
                'updatedAt' => $genre->getUpdateAt()?->format('d/m/Y'),
            ]
        ]);
    }
    
    #[Route('/genre/{id}', name: 'api_genre_delete', methods: ['DELETE'])]
    public function deleteGenre(?Genre $genre): JsonResponse
    {
        if (!$genre) {
            $this->logger->error('API: Attempt to delete non-existent genre.', ['id' => $genre->getId()]);
            return $this->json(['success' => false, 'error' => 'Genre non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        try {
            $id = $genre->getId();
            $this->entityManager->remove($genre);
            $this->entityManager->flush();
            $this->logger->notice('API: Genre successfully deleted.', ['id' => $id]);
            
            return $this->json(['success' => true, 'message' => 'Genre supprimé avec succès.'], Response::HTTP_NO_CONTENT); 
        } catch (\Exception $e) {
            $this->logger->critical('API: Error deleting genre.', ['id' => $genre->getId(), 'error' => $e->getMessage()]);
            return $this->json(['success' => false, 'error' => 'Impossible de supprimer le genre (potentiellement lié à des jeux vidéo).'], Response::HTTP_CONFLICT); 
        }
    }

    #[Route('/collection/{id}', name: 'api_collection_show', methods: ['GET'])]
    public function showCollection(?Utilisateur $utilisateur): JsonResponse
    {
        if (!$utilisateur) {
            $this->logger->notice('API: Attempt to show collection for non-existent user.', ['id' => $utilisateur->getId()]);
            return $this->json(['success' => false, 'error' => 'Utilisateur non trouvé.'], Response::HTTP_NOT_FOUND);
        }

        $this->logger->info('API: Show collection for user.', ['user_id' => $utilisateur->getId()]);
        
        $collects = $utilisateur->getCollects();
        $data = array_map(fn($collect) => [
            'collectId' => $collect->getId(),
            'jeuVideoTitre' => $collect->getJeuVideo()->getTitre(),
            'statut' => $collect->getStatut()->getLabel(), 
            'prixAchat' => number_format($collect->getPrixAchat(), 2, ',', ' '),
            'prixOfficiel' => number_format($collect->getJeuVideo()->getPrix(), 2, ',', ' '),
            'dateAchat' => $collect->getDateAchat()?->format('d/m/Y'),
        ], $collects->toArray());

        return $this->json([
            'success' => true,
            'utilisateurPseudo' => $utilisateur->getPseudo(),
            'count' => count($data),
            'data' => $data,
        ]);
    }
}