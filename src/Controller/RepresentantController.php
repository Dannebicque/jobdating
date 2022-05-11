<?php

namespace App\Controller;

use App\Entity\Constante;
use App\Entity\Representant;
use App\Form\Representant1Type;
use App\Repository\RepresentantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\ByteString;

#[Route('/espace-entreprise/representant')]
class RepresentantController extends AbstractController
{
    #[Route('/new', name: 'app_representant_new', methods: ['GET', 'POST'])]
    public function new(
        MailerInterface $mailer,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher,
        Request $request,
        RepresentantRepository $representantRepository
    ): Response {
        $representant = new Representant();
        $representant->setEntreprise($this->getUser()?->getEntreprise());
        $form = $this->createForm(Representant1Type::class, $representant, ['edit' => false]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $representant->setRoles(['ROLE_ENTREPRISE']);
            $representantRepository->add($representant);
            if ($representant->isSendPassword() === true) {
                $password = ByteString::fromRandom(10)->toString();
                $representant->setPassword($passwordHasher->hashPassword($representant, $password));

                $mailer->send((new TemplatedEmail())
                    ->from(new Address(Constante::EMAIL_EXPEDITEUR, Constante::NOM_EXPEDITEUR))
                    ->to($representant->getEmail())
                    ->subject('Création d\'un compte pour le JobDating de l\'IUT de Troyes')
                    ->htmlTemplate('representant/creation_compte.html.twig')
                    ->context([
                        'auteur' => $this->getUser(),
                        'user' => $representant,
                        'entreprise' => $this->getUser()?->getEntreprise(),
                        'password' => $password
                    ]));

                $entityManager->flush();
            }

            return $this->redirectToRoute('app_espace_entreprise', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('representant/new.html.twig', [
            'representant' => $representant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_representant_show', methods: ['GET'])]
    public function show(Representant $representant): Response
    {
        return $this->render('representant/show.html.twig', [
            'representant' => $representant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_representant_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Representant $representant,
        RepresentantRepository $representantRepository
    ): Response {
        if ($representant->getEntreprise() !== $this->getUser()->getEntreprise()) {
            throw new AccessDeniedException();
        }
        $form = $this->createForm(Representant1Type::class, $representant, ['edit' => true]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $representantRepository->add($representant);

            return $this->redirectToRoute('app_espace_entreprise', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('representant/edit.html.twig', [
            'representant' => $representant,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_representant_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Representant $representant,
        RepresentantRepository $representantRepository
    ): Response {
        if ($representant->getEntreprise() !== $this->getUser()->getEntreprise()) {
            throw new AccessDeniedException();
        }

        if ($this->isCsrfTokenValid('delete' . $representant->getId(), $request->request->get('_token'))) {
            $representantRepository->remove($representant);
        }

        return $this->redirectToRoute('app_espace_entreprise', [], Response::HTTP_SEE_OTHER);
    }
}
