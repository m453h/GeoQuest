<?php

namespace App\Entity\Location;

use App\Repository\Location\RegionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RegionRepository::class)]
#[ORM\Table(name: 'cfg_regions')]
class Region
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\OneToMany(mappedBy: 'region', targetEntity: SubRegion::class)]
    private Collection $subRegions;

    public function __construct()
    {
        $this->subRegions = new ArrayCollection();
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

    /**
     * @return Collection<int, SubRegion>
     */
    public function getSubRegions(): Collection
    {
        return $this->subRegions;
    }

    public function addSubRegion(SubRegion $subRegion): static
    {
        if (!$this->subRegions->contains($subRegion)) {
            $this->subRegions->add($subRegion);
            $subRegion->setRegion($this);
        }

        return $this;
    }

    public function removeSubRegion(SubRegion $subRegion): static
    {
        if ($this->subRegions->removeElement($subRegion)) {
            // set the owning side to null (unless already changed)
            if ($subRegion->getRegion() === $this) {
                $subRegion->setRegion(null);
            }
        }

        return $this;
    }
}
