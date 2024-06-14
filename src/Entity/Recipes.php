<?php

namespace App\Entity;

use App\Repository\RecipesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RecipesRepository::class)]
class Recipes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var Collection<int, Ingredients>
     */
    #[ORM\ManyToMany(targetEntity: Ingredients::class, inversedBy: 'recipes')]
    private Collection $ingredients;

    #[ORM\ManyToOne(inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $User = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $reservationAt = null;

    #[ORM\Column]
    private ?bool $isPaid = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isStarter = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPlate = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isDessert = null;

    public function __construct()
    {
        $this->ingredients = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Ingredients>
     */
    public function getIngredients(): Collection
    {
        return $this->ingredients;
    }

    public function addIngredient(Ingredients $ingredient): static
    {
        if (!$this->ingredients->contains($ingredient)) {
            $this->ingredients->add($ingredient);
        }

        return $this;
    }

    public function removeIngredient(Ingredients $ingredient): static
    {
        $this->ingredients->removeElement($ingredient);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getReservationAt(): ?\DateTimeImmutable
    {
        return $this->reservationAt;
    }

    public function setReservationAt(\DateTimeImmutable $reservationAt): static
    {
        $this->reservationAt = $reservationAt;

        return $this;
    }

    public function isPaid(): ?bool
    {
        return $this->isPaid;
    }

    public function setPaid(bool $isPaid): static
    {
        $this->isPaid = $isPaid;

        return $this;
    }

    public function isStarter(): ?bool
    {
        return $this->isStarter;
    }

    public function setStarter(?bool $isStarter): static
    {
        $this->isStarter = $isStarter;

        return $this;
    }

    public function isPlate(): ?bool
    {
        return $this->isPlate;
    }

    public function setPlate(?bool $isPlate): static
    {
        $this->isPlate = $isPlate;

        return $this;
    }

    public function isDessert(): ?bool
    {
        return $this->isDessert;
    }

    public function setDessert(?bool $isDessert): static
    {
        $this->isDessert = $isDessert;

        return $this;
    }
}
