<?php

namespace App\Entity;

use App\Repository\TreatmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Delete;

#[ApiResource(
    operations: [
        new GetCollection(security: "is_granted('ROLE_VETERINAIRE')", securityMessage: 'You are not allowed to get treatments'),
        new Post(security: "is_granted('ROLE_VETERINAIRE')", securityMessage: 'You are not allowed to create this treatment'),
        new Get(security: "is_granted('ROLE_VETERINAIRE')", securityMessage: 'You are not allowed to get this treatment'),
        new Patch(security: "is_granted('ROLE_VETERINAIRE')", securityMessage: 'You are not allowed to edit this treatment'),
        new Delete(security: "is_granted('ROLE_VETERINAIRE')", securityMessage: 'You are not allowed to delete this treatment'),
    ],
    forceEager: false
)]
#[ORM\Entity(repositoryClass: TreatmentRepository::class)]
class Treatment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column]
    private ?int $duration = null;

    /**
     * @var Collection<int, Apointement>
     */
    #[ORM\ManyToMany(targetEntity: Apointement::class, mappedBy: 'treatment')]
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

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
            $apointement->addTreatment($this);
        }

        return $this;
    }

    public function removeApointement(Apointement $apointement): static
    {
        if ($this->apointements->removeElement($apointement)) {
            $apointement->removeTreatment($this);
        }

        return $this;
    }
}
