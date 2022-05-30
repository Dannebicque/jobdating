<?php

namespace App\Classes;

use App\Repository\CandidatureRepository;
use App\Repository\EntrepriseRepository;
use App\Repository\OffreRepository;
use App\Repository\RepresentantRepository;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Knp\Snappy\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Twig\Environment;

class Export
{
    protected Spreadsheet $spreadsheet;
    protected Worksheet $sheet;

    public function __construct(
        private Pdf $knpSnappyPdf,
        private Environment $twig,
        private CandidatureRepository $candidatureRepository,
        private OffreRepository $offreRepository,
        private EntrepriseRepository $entrepriseRepository,
        private RepresentantRepository $representantRepository
    ) {
        $this->spreadsheet = new Spreadsheet();
        $this->sheet = $this->spreadsheet->getActiveSheet();


    }

    public function save(string $name): StreamedResponse
    {
        $writer = new Xlsx($this->spreadsheet);

        return new StreamedResponse(
            static function() use ($writer) {
                $writer->save('php://output');
            },
            Response::HTTP_OK,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment;filename="' . $name . '.xlsx"',
            ]
        );
    }

    public function exportRepresentant()
    {
        $representants = $this->representantRepository->findAll();
        $this->sheet->setCellValue('A1', 'Civilité');
        $this->sheet->setCellValue('B1', 'Nom');
        $this->sheet->setCellValue('C1', 'Prénom');
        $this->sheet->setCellValue('D1', 'Email');
        $this->sheet->setCellValue('E1', 'Entreprise');
        $this->sheet->setCellValue('F1', 'Adresse');
        $this->sheet->setCellValue('G1', 'Code Postal');
        $this->sheet->setCellValue('H1', 'Ville');
        foreach ($representants as $key => $representant) {
            $this->sheet->setCellValue('A' . ($key + 2), $representant->getCivilite());
            $this->sheet->setCellValue('B' . ($key + 2), $representant->getNom());
            $this->sheet->setCellValue('C' . ($key + 2), $representant->getPrenom());
            $this->sheet->setCellValue('D' . ($key + 2), $representant->getEmail());
            $this->sheet->setCellValue('E' . ($key + 2), $representant->getEntreprise()->getRaisonSociale());
            $this->sheet->setCellValue('F' . ($key + 2), $representant->getEntreprise()->getAdresse());
            $this->sheet->setCellValue('G' . ($key + 2), $representant->getEntreprise()->getCodePostal());
            $this->sheet->setCellValue('H' . ($key + 2), $representant->getEntreprise()->getVille());
        }

        return $this->save('representants');
    }

    public function exportEntreprise()
    {
        $entreprises = $this->entrepriseRepository->findAll();

        $this->sheet->setCellValue('A1', 'Entreprise');
        $this->sheet->setCellValue('B1', 'Adresse');
        $this->sheet->setCellValue('C1', 'Code Postal');
        $this->sheet->setCellValue('D1', 'Ville');
        $this->sheet->setCellValue('E1', 'Participe ?');
        $this->sheet->setCellValue('F1', 'Heure Début');
        $this->sheet->setCellValue('G1', 'Heure Fin');
        $this->sheet->setCellValue('H1', 'Nb Stand');
        $this->sheet->setCellValue('I1', 'Civilité');
        $this->sheet->setCellValue('J1', 'Nom');
        $this->sheet->setCellValue('K1', 'Prénom');
        $this->sheet->setCellValue('L1', 'Email');
        foreach ($entreprises as $key => $entreprise) {
            $this->sheet->setCellValue('A' . ($key + 2), $entreprise->getRaisonSociale());
            $this->sheet->setCellValue('B' . ($key + 2), $entreprise->getAdresse());
            $this->sheet->setCellValue('C' . ($key + 2), $entreprise->getCodePostal());
            $this->sheet->setCellValue('D' . ($key + 2), $entreprise->getVille());
            $this->sheet->setCellValue('E' . ($key + 2), $entreprise->getParticipe());
            $this->sheet->setCellValue('F' . ($key + 2), $entreprise->getHeureDebut()?->format('H:i'));
            $this->sheet->setCellValue('G' . ($key + 2), $entreprise->getHeureFin()?->format('H:i'));
            $this->sheet->setCellValue('H' . ($key + 2), $entreprise->getNbStands());
            $this->sheet->setCellValue('I' . ($key + 2), $entreprise->getRepresentants()->first()->getCivilite());
            $this->sheet->setCellValue('J' . ($key + 2), $entreprise->getRepresentants()->first()->getNom());
            $this->sheet->setCellValue('K' . ($key + 2), $entreprise->getRepresentants()->first()->getPrenom());
            $this->sheet->setCellValue('L' . ($key + 2), $entreprise->getRepresentants()->first()->getEmail());
        }

        return $this->save('entreprises');

    }

    public function exportOffre()
    {
        $offres = $this->offreRepository->findAll();

        $this->sheet->setCellValue('A1', 'Offre');
        $this->sheet->setCellValue('B1', 'Entreprise');
        $this->sheet->setCellValue('C1', 'Parcours');
        foreach ($offres as $key => $offre) {
            $this->sheet->setCellValue('A' . ($key + 2), $offre->getTitre());
            $this->sheet->setCellValue('B' . ($key + 2), $offre->getEntreprise()->getRaisonSociale());
            foreach ($offre->getParcours() as $parcours) {
                $textParcours = $parcours->getLibelle().'; ';

            }
            $this->sheet->setCellValue('C' . ($key + 2), $textParcours);
        }

        return $this->save('offres');

    }

    public function exportPlanning()
    {
        $entreprises = $this->entrepriseRepository->findAll();
        $allCandidatures = $this->candidatureRepository->findBy([], ['creneau' => 'ASC']);

        $candidatures = [];
        foreach ($entreprises as $entreprise)
        {
            $candidatures[$entreprise->getId()] = [];
            for($i = 1; $i < $entreprise->getNbStands(); $i++) {
                $candidatures[$entreprise->getId()][$i] = [];
            }
        }

        foreach ($allCandidatures as $candidature) {
            $candidatures[$candidature->getOffre()->getEntreprise()->getId()][$candidature->getStand()][] = $candidature;
        }

        $html = $this->twig->render('pdf/planning.html.twig', array(
            'entreprises' => $this->entrepriseRepository->findAll(),
            'candidatures' => $candidatures
        ));

        return new PdfResponse(
            $this->knpSnappyPdf->getOutputFromHtml($html, ['enable-local-file-access' => true]),
            'planning.pdf'
        );
    }
}
