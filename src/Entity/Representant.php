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

    private bool $sendPassword = true;

    #[ORM\ManyToOne(targetEntity: Entreprise::class, inversedBy: 'representants')]
    private ?Entreprise $entreprise;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getEntreprise(): ?Entreprise
    {
        return $this->entreprise;
    }

    public function setEntreprise(?Entreprise $entreprise): self
    {
        $this->entreprise = $entreprise;

        return $this;
    }


}
