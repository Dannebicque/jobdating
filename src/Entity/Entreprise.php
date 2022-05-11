<?php

namespace App\Entity;

use App\Repository\EntrepriseRepository;
use DateTime;
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

    #[ORM\Column(type: 'integer')]
    private ?int $nbStands = 1;

    #[ORM\OneToMany(mappedBy: 'contactEntreprise', targetEntity: Representant::class)]
    private Collection $autresContacts;

    #[ORM\Column(type: 'boolean')]
    private ?bool $participe;

    #[ORM\Column(type: 'time')]
    private ?\DateTimeInterface $heureDebut;

    #[ORM\Column(type: 'time')]
    private ?\DateTimeInterface $heureFin;

    public function __construct()
    {
        $this->setHeureDebut(new DateTime('14:00'));
        $this->setHeureFin(new DateTime('18:00'));
        $this->offres = new ArrayCollection();
        $this->autresContacts = new ArrayCollection();
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

    public function getNbStands(): ?int
    {
        return $this->nbStands;
    }

    public function setNbStands(int $nbStands): self
    {
        $this->nbStands = $nbStands;

        return $this;
    }

    /**
     * @return Collection<int, Representant>
     */
    public function getAutresContacts(): Collection
    {
        return $this->autresContacts;
    }

    public function addAutresContact(Representant $autresContact): self
    {
        if (!$this->autresContacts->contains($autresContact)) {
            $this->autresContacts[] = $autresContact;
            $autresContact->setContactEntreprise($this);
        }

        return $this;
    }

    public function removeAutresContact(Representant $autresContact): self
    {
        if ($this->autresContacts->removeElement($autresContact)) {
            // set the owning side to null (unless already changed)
            if ($autresContact->getContactEntreprise() === $this) {
                $autresContact->setContactEntreprise(null);
            }
        }

        return $this;
    }

    public function getParticipe(): ?bool
    {
        return $this->participe;
    }

    public function setParticipe(bool $participe): self
    {
        $this->participe = $participe;

        return $this;
    }

    public function getHeureDebut(): ?\DateTimeInterface
    {
        return $this->heureDebut;
    }

    public function setHeureDebut(\DateTimeInterface $heureDebut): self
    {
        $this->heureDebut = $heureDebut;

        return $this;
    }

    public function getHeureFin(): ?\DateTimeInterface
    {
        return $this->heureFin;
    }

    public function setHeureFin(\DateTimeInterface $heureFin): self
    {
        $this->heureFin = $heureFin;

        return $this;
    }
}
