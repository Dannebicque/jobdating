<?php

namespace App\Controller;

use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspaceEntrepriseController extends AbstractController
{
    #[Route('/espace/entreprise', name: 'app_espace_entreprise')]
    public function index(
        OffreRepository $offreRepository
    ): Response
    {
        $offres = $offreRepository->findByEntreprise($this->getUser());

        return $this->render('espace_entreprise/index.html.twig', [
            'offres' => $offres,
        ]);
    }
}
