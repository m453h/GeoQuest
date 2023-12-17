<?php

namespace App\Entity\Configuration;

use App\Repository\Configuration\FactTypeRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactTypeRepository::class)]
#[ORM\Table(name: 'cfg_fact_types')]
class FactType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 255)]
    private ?string $apiField = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $questionPrompt = null;

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

    public function getApiField(): ?string
    {
        return $this->apiField;
    }

    public function setApiField(string $apiField): static
    {
        $this->apiField = $apiField;

        return $this;
    }

    public function getQuestionPrompt(): ?string
    {
        return $this->questionPrompt;
    }

    public function setQuestionPrompt(string $questionPrompt): static
    {
        $this->questionPrompt = $questionPrompt;

        return $this;
    }
}
