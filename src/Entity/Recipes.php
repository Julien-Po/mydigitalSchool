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

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'recipes')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    #[ORM\Column(type: 'datetime_immutable')]
    private ?\DateTimeImmutable $createdAt = null;


    #[ORM\Column(type: 'boolean')]
    private ?bool $isPaid = null;

    #[ORM\Column(nullable: true, type: 'boolean')]
    private ?bool $isStarter = null;

    #[ORM\Column(nullable: true, type: 'boolean')]
    private ?bool $isPlate = null;

    #[ORM\Column(nullable: true, type: 'boolean')]
    private ?bool $isDessert = null;

    #[ORM\ManyToOne(targetEntity: Calendar::class, inversedBy: 'recipes')]
    private ?Calendar $calendar = null;

    #[ORM\Column(nullable: true, type: 'boolean')]
    private ?bool $isServed = null;

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
        return $this->user;
    }

    public function setUser(?User $User): static
    {
        $this->user = $User;

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

    public function getCalendar(): ?Calendar
    {
        return $this->calendar;
    }

    public function setCalendar(?Calendar $calendar): static
    {
        $this->calendar = $calendar;

        return $this;
    }

    public function isServed(): ?bool
    {
        return $this->isServed;
    }

    public function setServed(?bool $isServed): static
    {
        $this->isServed = $isServed;

        return $this;
    }
}
