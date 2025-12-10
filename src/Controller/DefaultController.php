<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface;

class DefaultController extends AbstractController
{
    #[Route('/', name: 'homepage')]
    public function index(LoggerInterface $logger): Response
    {
        $logger->info('Accès à la page d\'accueil (homepage).');
        
        return $this->render('default/index.html.twig', [
            'project_name' => 'Mon Projet Symfony',
            'students' => [
                ['firstname' => 'Andréas', 'lastname' => 'Rey Malissein'],
                ['firstname' => 'Joachim', 'lastname' => 'Fevre'],
            ],
            'group' => 'G8A',
        ]);
    }
}