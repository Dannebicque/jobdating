<?php

namespace App\Controller;

use App\Classes\Creneaux;
use App\Entity\Candidature;
use App\Entity\Offre;
use App\Form\CandidatureType;
use App\Repository\CandidatureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/espace-etudiant/offres')]
class OffreEtudiantController extends AbstractController
{
    #[Route('/mes-creneaux', name: 'app_espace_etudiant_mes_creneaux', methods: ['GET'])]
    public function mesCreneaux(
        CandidatureRepository $candidatureRepository,
    ): Response {
        $candidatures = $candidatureRepository->findBy(['etudiant' => $this->getUser()], ['creneau' => 'ASC']);

        return $this->render('offre-etudiant/mesCreneaux.html.twig', [
            'candidatures' => $candidatures,
        ]);
    }

    #[Route('/{id}', name: 'app_offre_etudiant_show', methods: ['GET|POST'])]
    public function show(
        Creneaux $creneaux,
        Request $request,
        CandidatureRepository $candidatureRepository,
        EntityManagerInterface $entityManager,
        Offre $offre
    ): Response {

        if (!in_array($this->getUser()?->getDiplome()?->getId(), $offre->getDiplome(), true)) {
            $this->addFlash('danger', 'Vous ne pouvez pas accéder à cette offre.');

            return $this->redirectToRoute('app_espace_etudiant_mes_creneaux');
        }

        $candidature = $candidatureRepository->findOneBy(['offre' => $offre, 'etudiant' => $this->getUser()]);
        $creneaux->setEntreprise($offre->getEntreprise());
        if ($candidature === null) {
            $candidature = new Candidature($offre, $this->getUser());
        }
        $form = $this->createForm(CandidatureType::class, $candidature);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($candidature);
            $entityManager->flush();
            $this->addFlash('success', 'Votre candidature a bien été déposée.');
        }

        return $this->render('offre-etudiant/show.html.twig', [
            'offre' => $offre,
            'form' => $form->createView(),
            'candidature' => $candidature,
            'creneaux' => $creneaux->getCreneaux(),
        ]);
    }


    #[Route('/{id}/{stand}/reserve', name: 'app_offre_etudiant_reserve_creneau', methods: ['GET|POST'])]
    public function reserveCreneau(
        EntityManagerInterface $entityManager,
        Creneaux $creneaux,
        int $stand,
        CandidatureRepository $candidatureRepository,
        Request $request,
        Offre $offre
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $heure = new \DateTime();
        $heure->setTimestamp(strtotime($data['creneau']));
        //regarder si une candidature existe déjà
        $candidature = $candidatureRepository->findOneBy(['offre' => $offre, 'etudiant' => $this->getUser()]);
        $creneaux->setEntreprise($offre->getEntreprise());
        if ($candidature === null) {
            $candidature = new Candidature($offre, $this->getUser(), $stand);
        }

        //vérifier si le créneau est disponible
        if ($creneaux->verifieSiToujoursDisponible($heure, $stand) === false) {
            $this->addFlash('danger', 'Ce créneau n\'est plus disponible.');

            return $this->json(['route' => $this->generateUrl('app_offre_etudiant_show', ['id' => $offre->getId()])],
                500);
        }

        $candidature->setCreneau($heure);
        $entityManager->persist($candidature);
        $entityManager->flush();

        $this->addFlash('success', 'Votre créneau est bien réservé.');

        return $this->json(['route' => $this->generateUrl('app_offre_etudiant_show', ['id' => $offre->getId()])]);
    }

    #[Route('/{id}/{stand}annule', name: 'app_offre_etudiant_annule_creneau', methods: ['GET|POST'])]
    public function annuleCreneau(
        EntityManagerInterface $entityManager,
        CandidatureRepository $candidatureRepository,
        Offre $offre,
        int $stand
    ): RedirectResponse {
        $candidature = $candidatureRepository->findOneBy(['offre' => $offre, 'etudiant' => $this->getUser(), 'stand' => $stand]);
        if ($candidature === null) {
            $this->addFlash('danger', 'Vous n\'avez aucune candidature pour cette offre.');

            return $this->redirectToRoute('app_offre_etudiant_show', ['id' => $offre->getId()]);
        }

        $candidature->setCreneau(null);
        $entityManager->persist($candidature);
        $entityManager->flush();

        $this->addFlash('success', 'Votre créneau a été annulé.');

        return $this->redirectToRoute('app_offre_etudiant_show', ['id' => $offre->getId()]);
    }

    #[Route('/candidature/{id}', name: 'app_candidature_delete', methods: ['POST'])]
    public function delete(
        Request $request,
        Candidature $candidature,
        CandidatureRepository $candidatureRepository
    ): Response {
        if ($this->isCsrfTokenValid('delete' . $candidature->getId(), $request->request->get('_token'))) {
            if ($candidature->getEtudiant() === $this->getUser()) {
                $candidatureRepository->remove($candidature);
                $this->addFlash('success', 'Candidature supprimée');
            } else {
                $this->addFlash('danger', 'Erreur lors de la suppression de la candidature.');
            }
        }

        return $this->redirectToRoute('app_espace_etudiant', [], Response::HTTP_SEE_OTHER);
    }
}
