<?php

namespace App\Entity\Location;

use App\Entity\Data\CountryFact;
use App\Repository\Location\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
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

    #[ORM\OneToMany(mappedBy: 'country', targetEntity: CountryFact::class, orphanRemoval: true)]
    private Collection $countryFacts;

    #[ORM\Column(nullable: true)]
    private ?bool $isFetched = null;

    public function __construct()
    {
        $this->countryFacts = new ArrayCollection();
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

    public function getSubRegion(): ?SubRegion
    {
        return $this->subRegion;
    }

    public function setSubRegion(?SubRegion $subRegion): static
    {
        $this->subRegion = $subRegion;

        return $this;
    }

    /**
     * @return Collection<int, CountryFact>
     */
    public function getCountryFacts(): Collection
    {
        return $this->countryFacts;
    }

    public function addCountryFact(CountryFact $countryFact): static
    {
        if (!$this->countryFacts->contains($countryFact)) {
            $this->countryFacts->add($countryFact);
            $countryFact->setCountry($this);
        }

        return $this;
    }

    public function removeCountryFact(CountryFact $countryFact): static
    {
        if ($this->countryFacts->removeElement($countryFact)) {
            // set the owning side to null (unless already changed)
            if ($countryFact->getCountry() === $this) {
                $countryFact->setCountry(null);
            }
        }

        return $this;
    }

    public function isIsFetched(): ?bool
    {
        return $this->isFetched;
    }

    public function setIsFetched(?bool $isFetched): static
    {
        $this->isFetched = $isFetched;

        return $this;
    }
}
