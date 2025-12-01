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

#[Route('/editeur')]
final class EditeurController extends AbstractController
{
    #[Route(name: 'app_editeur_index', methods: ['GET'])]
    public function index(EditeurRepository $editeurRepository, MenuBuilder $menuBuilder): Response
    {
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Éditeurs de jeux vidéo', ['route' => 'app_editeur_index']);
        return $this->render('editeur/index.html.twig', [
            'editeurs' => $editeurRepository->findAll(),
            'breadcrumb' => $breadcrumb,
        ]);
    }

    #[Route('/new', name: 'app_editeur_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, MenuBuilder $menuBuilder): Response
    {
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Éditeurs de jeux vidéo', ['route' => 'app_editeur_index']);
        $breadcrumb->addChild('Créer un nouvel éditeur');

        $editeur = new Editeur();
        $form = $this->createForm(EditeurType::class, $editeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($editeur);
            $entityManager->flush();

            return $this->redirectToRoute('app_editeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('editeur/new.html.twig', [
            'editeur' => $editeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_editeur_show', methods: ['GET'])]
    public function show(Editeur $editeur, MenuBuilder $menuBuilder): Response
    {
        $breadcrumb = $menuBuilder->createBreadcrumbMenu([]);
        $breadcrumb->addChild('Éditeurs de jeux vidéo', ['route' => 'app_editeur_index']);
        $breadcrumb->addChild($editeur->getNom());
        return $this->render('editeur/show.html.twig', [
            'editeur' => $editeur,
            'breadcrumb' => $breadcrumb,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_editeur_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Editeur $editeur, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(EditeurType::class, $editeur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_editeur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('editeur/edit.html.twig', [
            'editeur' => $editeur,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_editeur_delete', methods: ['POST'])]
    public function delete(Request $request, Editeur $editeur, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $editeur->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($editeur);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_editeur_index', [], Response::HTTP_SEE_OTHER);
    }
}
