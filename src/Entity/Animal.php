<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: AnimalRepository::class)]
class Animal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $spieces = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $birthdate = null;

    #[ORM\OneToOne(inversedBy: 'animal', cascade: ['persist', 'remove'])]
    private ?Media $picture = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    private ?User $owner = null;

    /**
     * @var Collection<int, Apointement>
     */
    #[ORM\OneToMany(targetEntity: Apointement::class, mappedBy: 'animal')]
    private Collection $apointements;

    public function __construct()
    {
        $this->apointements = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSpieces(): ?string
    {
        return $this->spieces;
    }

    public function setSpieces(string $spieces): static
    {
        $this->spieces = $spieces;

        return $this;
    }

    public function getBirthdate(): ?\DateTimeInterface
    {
        return $this->birthdate;
    }

    public function setBirthdate(\DateTimeInterface $birthdate): static
    {
        $this->birthdate = $birthdate;

        return $this;
    }

    public function getPicture(): ?Media
    {
        return $this->picture;
    }

    public function setPicture(?Media $picture): static
    {
        $this->picture = $picture;

        return $this;
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

    /**
     * @return Collection<int, Apointement>
     */
    public function getApointements(): Collection
    {
        return $this->apointements;
    }

    public function addApointement(Apointement $apointement): static
    {
        if (!$this->apointements->contains($apointement)) {
            $this->apointements->add($apointement);
            $apointement->setAnimal($this);
        }

        return $this;
    }

    public function removeApointement(Apointement $apointement): static
    {
        if ($this->apointements->removeElement($apointement)) {
            // set the owning side to null (unless already changed)
            if ($apointement->getAnimal() === $this) {
                $apointement->setAnimal(null);
            }
        }

        return $this;
    }
}
