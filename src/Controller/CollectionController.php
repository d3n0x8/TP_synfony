<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;
use App\Menu\MenuBuilder;

final class CollectionController extends AbstractController
{

    #[Route('/collection', name: 'app_collection_index')]
    public function index(UtilisateurRepository $utilisateurRepository, MenuBuilder $menuBuilder): Response
    {
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Collections de jeux vidéo', ['route' => 'app_collection_index']);

        return $this->render('collection/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
            'breadcrumb' => $breadcrumb,
        ]);
    }

    #[Route('/collection/utilisateur/{id}', name: 'app_collection_show')]
    public function show(Utilisateur $utilisateur, MenuBuilder $menuBuilder): Response
    {
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Collections de jeux vidéo', ['route' => 'app_collection_index']);
        $breadcrumb->addChild('Collection de ' . $utilisateur->getPseudo());
        return $this->render('collection/show.html.twig', [
            'utilisateur' => $utilisateur,
            'collects' => $utilisateur->getCollects(),
            'breadcrumb' => $breadcrumb,
        ]);
    }
}
