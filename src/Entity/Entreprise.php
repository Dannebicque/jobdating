<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EntrepriseRepository::class)]
class Entreprise
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $raison_sociale;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $adresse;

    #[ORM\Column(type: 'string', length: 5)]
    private ?string $code_postal;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $ville;

    #[ORM\Column(type: 'string', length: 255, nullable: true)]
    private ?string $salle;

    #[ORM\OneToMany(mappedBy: 'entreprise', targetEntity: Offre::class)]
    private Collection $offres;

    #[ORM\OneToOne(inversedBy: 'entreprise', targetEntity: Representant::class, cascade: ["persist"])]
    private ?Representant $representant;

    public function __construct()
    {
        $this->offres = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->raison_sociale;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRaisonSociale(): ?string
    {
        return $this->raison_sociale;
    }

    public function setRaisonSociale(string $raison_sociale): self
    {
        $this->raison_sociale = $raison_sociale;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getCodePostal(): ?string
    {
        return $this->code_postal;
    }

    public function setCodePostal(string $code_postal): self
    {
        $this->code_postal = $code_postal;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getSalle(): ?string
    {
        return $this->salle;
    }

    public function setSalle(string $salle): self
    {
        $this->salle = $salle;

        return $this;
    }

    /**
     * @return Collection<int, Offre>
     */
    public function getOffres(): Collection
    {
        return $this->offres;
    }

    public function addOffre(Offre $offre): self
    {
        if (!$this->offres->contains($offre)) {
            $this->offres[] = $offre;
            $offre->setEntreprise($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            // set the owning side to null (unless already changed)
            if ($offre->getEntreprise() === $this) {
                $offre->setEntreprise(null);
            }
        }

        return $this;
    }

    public function getRepresentant(): ?Representant
    {
        return $this->representant;
    }

    public function setRepresentant(?Representant $representant): self
    {
        $this->representant = $representant;

        return $this;
    }
}
