<?php

namespace App\Entity;

use App\Repository\RestrictionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RestrictionRepository::class)]
class Restriction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'restrictions')]
    private ?Post $post = null;

    #[ORM\ManyToOne(inversedBy: 'restrictions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $restrictedUser = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $restrictionType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }

    public function getRestrictedUser(): ?User
    {
        return $this->restrictedUser;
    }

    public function setRestrictedUser(?User $restrictedUser): static
    {
        $this->restrictedUser = $restrictedUser;

        return $this;
    }

    public function getRestrictionType(): ?string
    {
        return $this->restrictionType;
    }

    public function setRestrictionType(?string $restrictionType): static
    {
        $this->restrictionType = $restrictionType;

        return $this;
    }
}
