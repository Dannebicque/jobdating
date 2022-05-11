<?php

namespace App\Controller;

use App\Repository\OffreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspaceEntrepriseController extends AbstractController
{
    #[Route('/espace-entreprise', name: 'app_espace_entreprise')]
    public function index(
        OffreRepository $offreRepository
    ): Response
    {
        $offres = $offreRepository->findByEntreprise($this->getUser());

        return $this->render('espace_entreprise/index.html.twig', [
            'entreprise' => $this->getUser()->getEntreprise(),
            'offres' => $offres,
        ]);
    }

    #[Route('/espace-entreprise/candidatures', name: 'app_espace_entreprise_candidatures')]
    public function candidatures(
    ): Response
    {
        return $this->render('espace_entreprise/candidatures.html.twig', [
        ]);
    }

    #[Route('/espace-entreprise/planning', name: 'app_espace_entreprise_planning')]
    public function planning(
    ): Response
    {
        return $this->render('espace_entreprise/planning.html.twig', [
        ]);
    }
}
