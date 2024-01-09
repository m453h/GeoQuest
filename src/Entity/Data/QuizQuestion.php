<?php

namespace App\Entity\Data;

use App\Entity\Location\Country;
use App\Repository\Data\QuizQuestionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizQuestionRepository::class)]
#[ORM\Table(name: 'tbl_quiz_questions')]
class QuizQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?CountryFact $countryFact = null;

    #[ORM\ManyToOne]
    private ?Country $userAnswer = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $timeCompleted = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isCorrect = null;

    #[ORM\Column(nullable: true)]
    private ?array $options = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageURL = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prompt = null;

    #[ORM\ManyToOne(inversedBy: 'quizQuestions')]
    private ?Quiz $quiz = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCountryFact(): ?CountryFact
    {
        return $this->countryFact;
    }

    public function setCountryFact(?CountryFact $countryFact): static
    {
        $this->countryFact = $countryFact;

        return $this;
    }

    public function getUserAnswer(): ?Country
    {
        return $this->userAnswer;
    }

    public function setUserAnswer(?Country $userAnswer): static
    {
        $this->userAnswer = $userAnswer;

        return $this;
    }

    public function getTimeCompleted(): ?\DateTimeInterface
    {
        return $this->timeCompleted;
    }

    public function setTimeCompleted(?\DateTimeInterface $timeCompleted): static
    {
        $this->timeCompleted = $timeCompleted;

        return $this;
    }

    public function isIsCorrect(): ?bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(?bool $isCorrect): static
    {
        $this->isCorrect = $isCorrect;

        return $this;
    }

    public function getOptions(): ?array
    {
        return $this->options;
    }

    public function setOptions(?array $options): static
    {
        $this->options = $options;

        return $this;
    }

    public function getImageURL(): ?string
    {
        return $this->imageURL;
    }

    public function setImageURL(?string $imageURL): static
    {
        $this->imageURL = $imageURL;

        return $this;
    }

    public function getPrompt(): ?string
    {
        return $this->prompt;
    }

    public function setPrompt(?string $prompt): static
    {
        $this->prompt = $prompt;

        return $this;
    }

    public function getQuiz(): ?Quiz
    {
        return $this->quiz;
    }

    public function setQuiz(?Quiz $quiz): static
    {
        $this->quiz = $quiz;

        return $this;
    }
}
