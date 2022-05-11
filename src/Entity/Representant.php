<?php

namespace App\Entity;

use App\Repository\RepresentantRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RepresentantRepository::class)]
class Representant extends User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'representant', targetEntity: Entreprise::class)]
    private ?Entreprise $entreprise = null;

    #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: 'autresContacts')]
    private ?Entreprise $contactEntreprise;

    private bool $sendPassword = true;

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
        // unset the owning side of the relation if necessary
        if ($entreprise === null && $this->entreprise !== null) {
            $this->entreprise->setRepresentant(null);
        }

        // set the owning side of the relation if necessary
        if ($entreprise !== null && $entreprise->getRepresentant() !== $this) {
            $entreprise->setRepresentant($this);
        }

        $this->entreprise = $entreprise;

        return $this;
    }

    public function getContactEntreprise(): ?Entreprise
    {
        return $this->contactEntreprise;
    }

    public function setContactEntreprise(?Entreprise $contactEntreprise): self
    {
        $this->contactEntreprise = $contactEntreprise;

        return $this;
    }

    /**
     * @return bool
     */
    public function isSendPassword(): bool
    {
        return $this->sendPassword;
    }

    /**
     * @param bool $sendPassword
     */
    public function setSendPassword(bool $sendPassword): void
    {
        $this->sendPassword = $sendPassword;
    }


}
