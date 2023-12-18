<?php

namespace App\Entity\Data;

use App\Entity\Configuration\ContentType;
use App\Entity\Configuration\FactType;
use App\Entity\Location\Country;
use App\Repository\Data\CountryFactRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CountryFactRepository::class)]
#[ORM\Table(name: 'tbl_country_facts')]
class CountryFact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'countryFacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?FactType $factType = null;

    #[ORM\ManyToOne(inversedBy: 'countryFacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Country $country = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $content = null;

    #[ORM\Column]
    private ?bool $isUserCreated = null;

    #[ORM\ManyToOne(inversedBy: 'countryFacts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?ContentType $contentType = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFactType(): ?FactType
    {
        return $this->factType;
    }

    public function setFactType(?FactType $factType): static
    {
        $this->factType = $factType;

        return $this;
    }

    public function getCountry(): ?Country
    {
        return $this->country;
    }

    public function setCountry(?Country $country): static
    {
        $this->country = $country;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function isIsUserCreated(): ?bool
    {
        return $this->isUserCreated;
    }

    public function setIsUserCreated(bool $isUserCreated): static
    {
        $this->isUserCreated = $isUserCreated;

        return $this;
    }

    public function getContentType(): ?ContentType
    {
        return $this->contentType;
    }

    public function setContentType(?ContentType $contentType): static
    {
        $this->contentType = $contentType;

        return $this;
    }

}
