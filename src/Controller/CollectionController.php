<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Utilisateur;
use App\Repository\UtilisateurRepository;

final class CollectionController extends AbstractController
{

    #[Route('/collection', name: 'app_collection_index')]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        return $this->render('collection/index.html.twig', [
            'utilisateurs' => $utilisateurRepository->findAll(),
        ]);
    }

    #[Route('/collection/utilisateur/{id}', name: 'app_collection_show')]
    public function show(Utilisateur $utilisateur): Response
    {
        return $this->render('collection/show.html.twig', [
            'utilisateur' => $utilisateur,
            'collects' => $utilisateur->getCollects(),
        ]);
    }
}
