<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\Entreprise1Type;
use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/espace-entreprise/entreprise')]
class EntrepriseController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_entreprise_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Entreprise $entreprise, EntrepriseRepository $entrepriseRepository): Response
    {
        $form = $this->createForm(Entreprise1Type::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entrepriseRepository->add($entreprise);
            return $this->redirectToRoute('app_espace_entreprise', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('entreprise/edit.html.twig', [
            'entreprise' => $entreprise,
            'form' => $form,
        ]);
    }
}
