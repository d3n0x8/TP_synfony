<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Psr\Log\LoggerInterface; 

#[Route('/lucky')]
class LuckyController extends AbstractController
{
    #[Route('/number', name: 'lucky_number', methods: ['GET'])]
    public function number(LoggerInterface $logger): Response
    {
        $number = random_int(0, 100);
        
        $logger->debug('Génération d\'un nombre chanceux.', ['number' => $number]); 
        
        return $this->render('lucky/number.html.twig', [
            'number' => $number,
        ]);
    }
}