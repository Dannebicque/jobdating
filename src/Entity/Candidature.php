<?php

namespace App\Entity;

use App\Repository\CandidatureRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

#[ORM\Entity(repositoryClass: CandidatureRepository::class)]
#[Vich\Uploadable]
class Candidature
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Offre::class, inversedBy: 'candidatures')]
    private ?Offre $offre = null;

    #[ORM\ManyToOne(targetEntity: Etudiant::class, inversedBy: 'candidatures')]
    private ?Etudiant $etudiant = null;

    #[ORM\Column(type: 'time', nullable: true)]
    private ?\DateTimeInterface $creneau = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $cv = null;

    #[Vich\UploadableField(mapping: "cv_pdf", fileNameProperty: "cv", size: "cvPdfSize")]
    private ?File $cvPdfFile = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $cvPdfSize = null;

    #[ORM\Column(type: 'string', length: 50, nullable: true)]
    private ?string $lettre = null;

    #[Vich\UploadableField(mapping: "lettre_pdf", fileNameProperty: "lettre", size: "lettrePdfSize")]
    private ?File $lettrePdfFile = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $lettrePdfSize = null;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?DateTimeInterface $updatedAt = null;

    public function __construct(?Offre $offre, ?Etudiant $etudiant)
    {
        $this->offre = $offre;
        $this->etudiant = $etudiant;
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOffre(): ?Offre
    {
        return $this->offre;
    }

    public function setOffre(?Offre $offre): self
    {
        $this->offre = $offre;

        return $this;
    }

    public function getEtudiant(): ?Etudiant
    {
        return $this->etudiant;
    }

    public function setEtudiant(?Etudiant $etudiant): self
    {
        $this->etudiant = $etudiant;

        return $this;
    }

    public function getCreneau(): ?\DateTimeInterface
    {
        return $this->creneau;
    }

    public function setCreneau(?\DateTimeInterface $creneau): self
    {
        $this->creneau = $creneau;

        return $this;
    }

    public function getCv(): ?string
    {
        return $this->cv;
    }

    public function setCv(?string $cv): self
    {
        $this->cv = $cv;

        return $this;
    }

    public function getLettre(): ?string
    {
        return $this->lettre;
    }

    public function setLettre(?string $lettre): self
    {
        $this->lettre = $lettre;

        return $this;
    }

    public function getCvPdfSize(): ?int
    {
        return $this->cvPdfSize;
    }

    public function setCvPdfSize(?int $cvPdfSize): self
    {
        $this->cvPdfSize = $cvPdfSize;

        return $this;
    }

    public function getLettrePdfSize(): ?int
    {
        return $this->lettrePdfSize;
    }

    public function setLettrePdfSize(?int $lettrePdfSize): self
    {
        $this->lettrePdfSize = $lettrePdfSize;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function setCvPdfFile(?File $pdfFile = null): void
    {
        $this->cvPdfFile = $pdfFile;

        if (null !== $pdfFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getCvPdfFile(): ?File
    {
        return $this->cvPdfFile;
    }

    public function setLettrePdfFile(?File $pdfFile = null): void
    {
        $this->lettrePdfFile = $pdfFile;

        if (null !== $pdfFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }

    public function getLettrePdfFile(): ?File
    {
        return $this->lettrePdfFile;
    }

    public function hasCV(): string
    {
        return $this->cv !== null ? 'CV Déposé' : 'Pas de CV';
    }

    public function hasLettre(): string
    {
        return $this->lettre !== null ? 'Lettre Déposée' : 'Pas de Lettre';
    }
}
