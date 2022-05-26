<?php

namespace App\Classes;

use App\Entity\Entreprise;
use App\Repository\CandidatureRepository;

class Creneaux
{
    private ?Entreprise $entreprise;

    public function __construct(private CandidatureRepository $candidatureRepository)
    {
    }


    public function setEntreprise(?Entreprise $entreprise): void
    {
        $this->entreprise = $entreprise;
    }

    public function getCreneaux(): ?array
    {
        if ($this->entreprise === null) {
            return null;
        }

        $creneau = $this->entreprise->getHeureDebut();
        $fin = $this->entreprise->getHeureFin();
        if ($creneau === null || $fin === null) {
            return null;
        }
        $tCandidatures = $this->getCreneauxCandidatures();
        $tCreneaux = [];
        $fin = $fin->sub(new \DateInterval('PT30M'));
        while ($creneau <= $fin) {
            $fc = clone $creneau;
            $fc = $fc->add(new \DateInterval('PT30M'));
            for ($i = 1; $i <= $this->entreprise->getNbStands(); $i++) {
                $tCreneaux[] = [
                    'stands' => $i,
                    'debut' => $creneau,
                    'fin' => $fc,
                    'disponible' => !array_key_exists($creneau->format('H:i'), $tCandidatures[$i])
                ];
            }
            $creneau = $fc;
        }

        return $tCreneaux;
    }

    public function getCreneauxCandidatures(): array
    {
        $tCandiatures = [];
        $candidatures = $this->candidatureRepository->findByEntreprise($this->entreprise);

        for ($i = 1; $i <= $this->entreprise->getNbStands(); $i++) {
            $tCandiatures[$i] = [];
        }

        foreach ($candidatures as $candidature) {
            if ($candidature->getCreneau() !== null) {
                $tCandiatures[$candidature->getStand()][$candidature->getCreneau()->format('H:i')] = $candidature;
            }
        }


        return $tCandiatures;
    }

    public function verifieSiToujoursDisponible(\DateTimeInterface $creaneau, int $stand): bool
    {
        $tCandidatures = $this->getCreneauxCandidatures();
        if (array_key_exists($stand, $tCandidatures)) {
            if (array_key_exists($creaneau->format('H:i'), $tCandidatures[$stand])) {
                return false;
            }
        }

        return true;
    }
}
