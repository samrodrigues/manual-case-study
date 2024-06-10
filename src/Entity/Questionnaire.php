<?php

namespace App\Entity;

use App\Repository\QuestionnaireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: QuestionnaireRepository::class)]
class Questionnaire
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['questionnaire'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['questionnaire'])]
    private ?string $name = null;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\OneToMany(targetEntity: Question::class, mappedBy: 'questionnaire', orphanRemoval: true)]
    #[Groups(['questionnaire'])]
    private Collection $questions;

    /**
     * @var Collection<int, QuestionnaireSubmission>
     */
    #[ORM\OneToMany(targetEntity: QuestionnaireSubmission::class, mappedBy: 'questionnaire', orphanRemoval: true)]
    private Collection $questionnaireSubmissions;

    public function __construct()
    {
        $this->questions = new ArrayCollection();
        $this->questionnaireSubmissions = new ArrayCollection();
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
     * @return Collection<int, Question>
     */
    public function getQuestions(): Collection
    {
        return $this->questions;
    }

    public function addQuestion(Question $question): static
    {
        if (!$this->questions->contains($question)) {
            $this->questions->add($question);
            $question->setQuestionnaire($this);
        }

        return $this;
    }

    public function removeQuestion(Question $question): static
    {
        if ($this->questions->removeElement($question)) {
            // set the owning side to null (unless already changed)
            if ($question->getQuestionnaire() === $this) {
                $question->setQuestionnaire(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QuestionnaireSubmission>
     */
    public function getQuestionnaireSubmissions(): Collection
    {
        return $this->questionnaireSubmissions;
    }

    public function addQuestionnaireSubmission(QuestionnaireSubmission $questionnaireSubmission): static
    {
        if (!$this->questionnaireSubmissions->contains($questionnaireSubmission)) {
            $this->questionnaireSubmissions->add($questionnaireSubmission);
            $questionnaireSubmission->setQuestionnaire($this);
        }

        return $this;
    }

    public function removeQuestionnaireSubmission(QuestionnaireSubmission $questionnaireSubmission): static
    {
        if ($this->questionnaireSubmissions->removeElement($questionnaireSubmission)) {
            // set the owning side to null (unless already changed)
            if ($questionnaireSubmission->getQuestionnaire() === $this) {
                $questionnaireSubmission->setQuestionnaire(null);
            }
        }

        return $this;
    }
}
