<?php

namespace App\Controller;

use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspaceEtudiantController extends AbstractController
{
    #[Route('/espace-etudiant', name: 'app_espace_etudiant')]
    public function index(
        OffreRepository $offreRepository
    ): Response
    {
        $offres = $offreRepository->findByDiplome($this->getUser()->getDiplome());

        return $this->render('espace_etudiant/index.html.twig', [
            'offres' => $offres,
        ]);
    }
}
