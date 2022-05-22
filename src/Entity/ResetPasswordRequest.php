<?php

namespace App\Entity;

use App\Repository\ResetPasswordRequestRepository;
use Doctrine\ORM\Mapping as ORM;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestInterface;
use SymfonyCasts\Bundle\ResetPassword\Model\ResetPasswordRequestTrait;

#[ORM\Entity(repositoryClass: ResetPasswordRequestRepository::class)]
class ResetPasswordRequest implements ResetPasswordRequestInterface
{
    use ResetPasswordRequestTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private $id;

    #[ORM\ManyToOne(targetEntity: Etudiant::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Etudiant $user = null;

    #[ORM\ManyToOne(targetEntity: Admin::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Admin $admin;

    #[ORM\ManyToOne(targetEntity: Representant::class)]
    #[ORM\JoinColumn(nullable: true)]
    private ?Representant $representant;

    public function __construct(object $user, \DateTimeInterface $expiresAt, string $selector, string $hashedToken)
    {
        if ($user instanceof Etudiant) {
            $this->user = $user;
        } elseif ($user instanceof Admin) {
            $this->admin = $user;
        } elseif ($user instanceof Representant) {
            $this->representant = $user;
        }
        $this->initialize($expiresAt, $selector, $hashedToken);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): object
    {
        if ($this->user !== null) {
            return $this->user;
        }
        if ($this->admin !== null) {
            return $this->admin;
        }
        if ($this->representant !== null) {
            return $this->representant;
        }
    }
}
