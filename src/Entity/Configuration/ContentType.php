<?php

namespace App\Entity\Configuration;

use App\Entity\Data\CountryFact;
use App\Repository\Configuration\ContentTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContentTypeRepository::class)]
#[ORM\Table(name: 'cfg_content_types')]
class ContentType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;


    #[ORM\OneToMany(mappedBy: 'contentType', targetEntity: CountryFact::class)]
    private Collection $countryFacts;

    public function __construct()
    {
        $this->countryFacts = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

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
            $countryFact->setContentType($this);
        }

        return $this;
    }

    public function removeCountryFact(CountryFact $countryFact): static
    {
        if ($this->countryFacts->removeElement($countryFact)) {
            // set the owning side to null (unless already changed)
            if ($countryFact->getContentType() === $this) {
                $countryFact->setContentType(null);
            }
        }

        return $this;
    }

}
