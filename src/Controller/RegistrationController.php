<?php

namespace App\Controller;

use App\Entity\Constante;
use App\Entity\Entreprise;
use App\Entity\Etudiant;
use App\Entity\Representant;
use App\Entity\User;
use App\Form\EntrepriseType;
use App\Form\EtudiantType;
use App\Form\RegistrationFormType;
use App\Repository\EtudiantRepository;
use App\Repository\RepresentantRepository;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(): Response
    {
        return $this->render('registration/register.html.twig');
    }

    #[Route('/register-entreprise', name: 'app_register_entreprise')]
    public function registerEntreprise(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        LoginFormAuthenticator $authenticator,
        EntityManagerInterface $entityManager
    ): Response {
        $entreprise = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $entreprise);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $entreprise->getRepresentant()?->setRoles(['ROLE_ENTREPRISE']);
            $entreprise->getRepresentant()?->setPassword(
                $userPasswordHasher->hashPassword(
                    $entreprise->getRepresentant(),
                    $form->get('representant')->getData()->getPassword()
                )
            );

            $entityManager->persist($entreprise);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $entreprise->getRepresentant(),
                (new TemplatedEmail())
                    ->from(new Address(Constante::EMAIL_EXPEDITEUR, Constante::NOM_EXPEDITEUR))
                    ->to($entreprise->getRepresentant()?->getEmail())
                    ->subject('Merci de confirmer votre adresse email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
                    ->context(['user' => $entreprise->getRepresentant(), 'entreprise' => $entreprise])
            );

            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $entreprise->getRepresentant(),
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register_form_entreprise.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/register-etudiant', name: 'app_register_etudiant')]
    public function registerEtudiant(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        LoginFormAuthenticator $authenticator,
        EntityManagerInterface $entityManager
    ): Response {
        $user = new Etudiant();
        $form = $this->createForm(EtudiantType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address(Constante::EMAIL_EXPEDITEUR, Constante::NOM_EXPEDITEUR))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register_form_etudiant.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    public function verifyUserEmail(
        Request $request,
        RepresentantRepository $representantRepository,
        EtudiantRepository $etudiantRepository,
    ): Response {
        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $representant = $representantRepository->find($id);
        $etudiant = $etudiantRepository->find($id);

        if (null === $etudiant && null === $representant) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            if ($representant !== null) {
                $this->emailVerifier->handleEmailConfirmation($request, $representant);
            } else {
                $this->emailVerifier->handleEmailConfirmation($request, $etudiant);
            }
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        $this->addFlash('success', 'Votre email est confirmé.');

        if ($representant !== null) {
            return $this->redirectToRoute('app_espace_entreprise');
        }

        return $this->redirectToRoute('app_espace_etudiant');

    }
}
