<?php

namespace App\Entity;

use App\Repository\OffreRepository;
use DateTimeInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: OffreRepository::class)]
#[Vich\Uploadable]
class Offre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: 'offres')]
    private ?Entreprise $entreprise;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $titre = null;

    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $descriptif = null;

    #[Vich\UploadableField(mapping: "offre_pdf", fileNameProperty: "offrePdf", size: "offrePdfSize")]
    private ?File $pdfFile = null;

    #[ORM\Column(type: 'string', nullable: true)]
    private ?string $offrePdf = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $offrePdfSize = null;

    #[ORM\ManyToMany(targetEntity: Diplome::class, inversedBy: 'offres')]
    private Collection $diplomes;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $updatedAt = null;

    #[ORM\ManyToMany(targetEntity: Parcours::class, inversedBy: 'offres')]
    private Collection $parcours;

    #[ORM\OneToMany(mappedBy: 'offre', targetEntity: Candidature::class)]
    private Collection $candidatures;

    public function __construct()
    {
        $this->diplomes = new ArrayCollection();
        $this->parcours = new ArrayCollection();
        $this->candidatures = new ArrayCollection();
    }

    public function setPdfFile(?File $pdfFile = null): void
    {
        $this->pdfFile = $pdfFile;

        if (null !== $pdfFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getPdfFile(): ?File
    {
        return $this->pdfFile;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

        return $this;
    }

    public function getDescriptif(): ?string
    {
        return $this->descriptif;
    }

    public function setDescriptif(?string $descriptif): self
    {
        $this->descriptif = $descriptif;

        return $this;
    }

    public function getOffrePdf(): ?string
    {
        return $this->offrePdf;
    }

    public function setOffrePdf(?string $offrePdf): self
    {
        $this->offrePdf = $offrePdf;

        return $this;
    }

    /**
     * @return Collection<int, Diplome>
     */
    public function getDiplomes(): Collection
    {
        return $this->diplomes;
    }

    public function addDiplome(Diplome $diplome): self
    {
        if (!$this->diplomes->contains($diplome)) {
            $this->diplomes[] = $diplome;
        }

        return $this;
    }

    public function removeDiplome(Diplome $diplome): self
    {
        $this->diplomes->removeElement($diplome);

        return $this;
    }

    public function getOffrePdfSize(): ?int
    {
        return $this->offrePdfSize;
    }

    public function setOffrePdfSize(?int $offrePdfSize): self
    {
        $this->offrePdfSize = $offrePdfSize;

        return $this;
    }

    public function getUpdatedAt(): ?DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection<int, Parcours>
     */
    public function getParcours(): Collection
    {
        return $this->parcours;
    }

    public function addParcour(Parcours $parcour): self
    {
        if (!$this->parcours->contains($parcour)) {
            $this->parcours[] = $parcour;
        }

        return $this;
    }

    public function removeParcour(Parcours $parcour): self
    {
        $this->parcours->removeElement($parcour);

        return $this;
    }

    /**
     * @return Collection<int, Candidature>
     */
    public function getCandidatures(): Collection
    {
        return $this->candidatures;
    }

    public function addCandidature(Candidature $candidature): self
    {
        if (!$this->candidatures->contains($candidature)) {
            $this->candidatures[] = $candidature;
            $candidature->setOffre($this);
        }

        return $this;
    }

    public function removeCandidature(Candidature $candidature): self
    {
        if ($this->candidatures->removeElement($candidature)) {
            // set the owning side to null (unless already changed)
            if ($candidature->getOffre() === $this) {
                $candidature->setOffre(null);
            }
        }

        return $this;
    }
}
