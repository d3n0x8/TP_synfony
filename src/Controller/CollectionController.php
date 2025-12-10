<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Utilisateur;
use App\Entity\Collect;
use App\Form\CollectType;
use App\Repository\UtilisateurRepository;
use App\Menu\MenuBuilder;
use Psr\Log\LoggerInterface; 

final class CollectionController extends AbstractController
{

    #[Route('/collection', name: 'app_collection_index')]
    public function index(UtilisateurRepository $utilisateurRepository, MenuBuilder $menuBuilder, LoggerInterface $logger): Response
    {
        $logger->info('Accès à la liste des collections utilisateurs.'); 
        
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Collections de jeux vidéo', ['route' => 'app_collection_index']);

        return $this->render('collection/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
            'breadcrumb' => $breadcrumb,
        ]);
    }

    #[Route('/collection/utilisateur/{id}', name: 'app_collection_show')]
    public function show(Utilisateur $utilisateur, MenuBuilder $menuBuilder, LoggerInterface $logger): Response
    {
        $logger->info('Consultation de la collection d\'un utilisateur.', ['user_id' => $utilisateur->getId(), 'pseudo' => $utilisateur->getPseudo()]); 

        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Collections de jeux vidéo', ['route' => 'app_collection_index']);
        $breadcrumb->addChild('Collection de ' . $utilisateur->getPseudo());
        return $this->render('collection/show.html.twig', [
            'utilisateur' => $utilisateur,
            'collects' => $utilisateur->getCollects(),
            'breadcrumb' => $breadcrumb,
        ]);
    }

    #[Route('/collection/utilisateur/{id}/add', name: 'app_collection_add')]
    public function add(Request $request, Utilisateur $utilisateur, EntityManagerInterface $entityManager, MenuBuilder $menuBuilder, LoggerInterface $logger): Response
    {
        $logger->info('Affichage du formulaire d\'ajout de jeu vidéo.');

        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Collection', ['route' => 'app_collection_index']);
        $breadcrumb->addChild('Ajouter un jeu video');

        $collect = new Collect();
        $collect->setUtilisateur($utilisateur);
        $form = $this->createForm(CollectType::class, $collect);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($collect);
            $entityManager->flush();

            $logger->notice(
                'Ajout dans une collection.',
                [
                    'collect_id' => $collect->getId(),
                    'user_name' => $collect->getUtilisateur()->getNom(),
                    'game_title' => $collect->getJeuVideo()->getTitre()
                ]
            );

            return $this->redirectToRoute('app_collection_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('collection/new.html.twig', [
            'collect' => $collect,
            'form' => $form,
        ]);
    }
}