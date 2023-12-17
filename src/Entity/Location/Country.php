<?php

namespace App\Entity\Location;

use App\Repository\Location\CountryRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
#[ORM\Table(name: 'cfg_countries')]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'countries')]
    private ?SubRegion $subRegion = null;

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

    public function getSubRegion(): ?SubRegion
    {
        return $this->subRegion;
    }

    public function setSubRegion(?SubRegion $subRegion): static
    {
        $this->subRegion = $subRegion;

        return $this;
    }
}
