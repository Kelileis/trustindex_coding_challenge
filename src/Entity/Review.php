<?php

namespace App\Entity;

use App\Repository\ReviewRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ReviewRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Review
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $company_name = null;

    #[ORM\Column]
    #[Assert\NotNull]
    #[Assert\Range(min: 1, max: 5)]
    private ?int $rating = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    private ?string $review_text = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Email]
    private ?string $author_email = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCompanyName(): ?string
    {
        return $this->company_name;
    }

    public function setCompanyName(string $company_name): static
    {
        $this->company_name = $company_name;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getReviewText(): ?string
    {
        return $this->review_text;
    }

    public function setReviewText(string $review_text): static
    {
        $this->review_text = $review_text;

        return $this;
    }

    public function getAuthorEmail(): ?string
    {
        return $this->author_email;
    }

    public function setAuthorEmail(string $author_email): static
    {
        $this->author_email = $author_email;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): static
    {
        $this->created_at = new \DateTimeImmutable();

        return $this;
    }

    #[ORM\PrePersist]
    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): static
    {
        $this->updated_at = new \DateTimeImmutable();

        return $this;
    }
}
