<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspaceEtudiantController extends AbstractController
{
    #[Route('/espace/etudiant', name: 'app_espace_etudiant')]
    public function index(): Response
    {
        return $this->render('espace_etudiant/index.html.twig', [
            'controller_name' => 'EspaceEtudiantController',
        ]);
    }
}
