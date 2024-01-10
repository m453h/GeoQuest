<?php

namespace App\Entity\Data;

use App\Entity\Configuration\FactType;
use App\Entity\UserAccounts\User;
use App\Repository\Data\QuizRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuizRepository::class)]
#[ORM\Table(name: 'tbl_user_quizzes')]
class Quiz
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $identifier = null;

    #[ORM\ManyToOne]
    private ?FactType $quizType = null;

    #[ORM\ManyToOne(inversedBy: 'quizzes')]
    private ?User $quizOwner = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $timeStarted = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $timeCompleted = null;

    #[ORM\OneToMany(mappedBy: 'quiz', targetEntity: QuizQuestion::class)]
    private Collection $quizQuestions;

    public function __construct()
    {
        $this->quizQuestions = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function setIdentifier(?string $identifier): static
    {
        $this->identifier = $identifier;

        return $this;
    }

    public function getQuizType(): ?FactType
    {
        return $this->quizType;
    }

    public function setQuizType(?FactType $quizType): static
    {
        $this->quizType = $quizType;

        return $this;
    }

    public function getQuizOwner(): ?User
    {
        return $this->quizOwner;
    }

    public function setQuizOwner(?User $quizOwner): static
    {
        $this->quizOwner = $quizOwner;

        return $this;
    }

    public function getTimeStarted(): ?\DateTimeInterface
    {
        return $this->timeStarted;
    }

    public function setTimeStarted(?\DateTimeInterface $timeStarted): static
    {
        $this->timeStarted = $timeStarted;

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

    /**
     * @return Collection<int, QuizQuestion>
     */
    public function getQuizQuestions(): Collection
    {
        return $this->quizQuestions;
    }

    public function addQuizQuestion(QuizQuestion $quizQuestion): static
    {
        if (!$this->quizQuestions->contains($quizQuestion)) {
            $this->quizQuestions->add($quizQuestion);
            $quizQuestion->setQuiz($this);
        }

        return $this;
    }

    public function removeQuizQuestion(QuizQuestion $quizQuestion): static
    {
        if ($this->quizQuestions->removeElement($quizQuestion)) {
            // set the owning side to null (unless already changed)
            if ($quizQuestion->getQuiz() === $this) {
                $quizQuestion->setQuiz(null);
            }
        }

        return $this;
    }

    public function computeTotalScore(): int
    {
        $total = 0;
        foreach($this->quizQuestions as $question) {
            if($question->isIsCorrect() === true) {
                $total += 10;
            }
        }
        return $total;
    }

    public function getRemainingQuestions(): int
    {
        $progress = 0;
        $totalQuestions = 0;

        foreach($this->quizQuestions as $question) {
            $totalQuestions++;
            if($question->getTimeCompleted() != null) {
                $progress += 1;
            }
        }
        return ($totalQuestions - $progress);
    }
}
