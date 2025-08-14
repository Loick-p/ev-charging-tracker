<?php

namespace App\Entity;

use App\Repository\CarRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CarRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Car
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'cars')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column(length: 255)]
    private ?string $brand = null;

    #[ORM\Column(length: 255)]
    private ?string $model = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column]
    private ?int $range = null;

    #[ORM\Column]
    private ?float $batteryCapacity = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    /**
     * @var Collection<int, Charging>
     */
    #[ORM\OneToMany(targetEntity: Charging::class, mappedBy: 'car', orphanRemoval: true)]
    private Collection $chargings;

    public function __construct()
    {
        $this->chargings = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getBrand(): ?string
    {
        return $this->brand;
    }

    public function setBrand(string $brand): static
    {
        $this->brand = $brand;

        return $this;
    }

    public function getModel(): ?string
    {
        return $this->model;
    }

    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): static
    {
        $this->year = $year;

        return $this;
    }

    public function getRange(): ?int
    {
        return $this->range;
    }

    public function setRange(int $range): static
    {
        $this->range = $range;

        return $this;
    }

    public function getBatteryCapacity(): ?float
    {
        return $this->batteryCapacity;
    }

    public function setBatteryCapacity(float $batteryCapacity): static
    {
        $this->batteryCapacity = $batteryCapacity;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getDisplayName(): string
    {
        return $this->brand . ' ' . $this->model . ' ' . $this->year;
    }

    /**
     * @return Collection<int, Charging>
     */
    public function getChargings(): Collection
    {
        return $this->chargings;
    }

    public function addCharging(Charging $charging): static
    {
        if (!$this->chargings->contains($charging)) {
            $this->chargings->add($charging);
            $charging->setCar($this);
        }

        return $this;
    }

    public function removeCharging(Charging $charging): static
    {
        if ($this->chargings->removeElement($charging)) {
            // set the owning side to null (unless already changed)
            if ($charging->getCar() === $this) {
                $charging->setCar(null);
            }
        }

        return $this;
    }

    #[ORM\PrePersist]
    public function setCreatedAtValue(): void
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    #[ORM\PreUpdate]
    public function setUpdatedAtValue(): void
    {
        $this->updatedAt = new \DateTimeImmutable();
    }
}
