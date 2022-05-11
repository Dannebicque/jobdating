<?php

namespace App\Entity;

use App\Repository\ParcoursRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParcoursRepository::class)]
class Parcours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id;

    #[ORM\ManyToOne(targetEntity: Diplome::class, inversedBy: 'parcours')]
    private ?Diplome $diplome;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $libelle;

    #[ORM\Column(type: 'string', length: 20)]
    private ?string $sigle;

    #[ORM\Column(type: 'boolean')]
    private ?bool $alternance = true;

    #[ORM\ManyToMany(targetEntity: Offre::class, mappedBy: 'parcours')]
    private Collection $offres;

    public function __construct()
    {
        $this->offres = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiplome(): ?Diplome
    {
        return $this->diplome;
    }

    public function setDiplome(?Diplome $diplome): self
    {
        $this->diplome = $diplome;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getSigle(): ?string
    {
        return $this->sigle;
    }

    public function setSigle(string $sigle): self
    {
        $this->sigle = $sigle;

        return $this;
    }

    public function getAlternance(): ?bool
    {
        return $this->alternance;
    }

    public function setAlternance(bool $alternance): self
    {
        $this->alternance = $alternance;

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
            $offre->addParcour($this);
        }

        return $this;
    }

    public function removeOffre(Offre $offre): self
    {
        if ($this->offres->removeElement($offre)) {
            $offre->removeParcour($this);
        }

        return $this;
    }

    public function display(): string
    {
        return $this->getLibelle().' (B.U.T. '.$this->getDiplome()->getSigle().')';
    }
}
