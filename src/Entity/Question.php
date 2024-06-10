<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['questionnaire'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['questionnaire'])]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'questions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Questionnaire $questionnaire = null;

    /**
     * @var Collection<int, Option>
     */
    #[ORM\OneToMany(targetEntity: Option::class, mappedBy: 'question', orphanRemoval: true)]
    #[Groups(['questionnaire'])]
    private Collection $options;

    /**
     * @var Collection<int, QuestionResponse>
     */
    #[ORM\OneToMany(targetEntity: QuestionResponse::class, mappedBy: 'question', orphanRemoval: true)]
    private Collection $questionResponses;

    public function __construct()
    {
        $this->options = new ArrayCollection();
        $this->questionResponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $text): static
    {
        $this->text = $text;

        return $this;
    }

    public function getQuestionnaire(): ?Questionnaire
    {
        return $this->questionnaire;
    }

    public function setQuestionnaire(?Questionnaire $questionnaire): static
    {
        $this->questionnaire = $questionnaire;

        return $this;
    }

    /**
     * @return Collection<int, Option>
     */
    public function getOptions(): Collection
    {
        return $this->options;
    }

    public function addOption(Option $option): static
    {
        if (!$this->options->contains($option)) {
            $this->options->add($option);
            $option->setQuestion($this);
        }

        return $this;
    }

    public function removeOption(Option $option): static
    {
        if ($this->options->removeElement($option)) {
            // set the owning side to null (unless already changed)
            if ($option->getQuestion() === $this) {
                $option->setQuestion(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, QuestionResponse>
     */
    public function getQuestionResponses(): Collection
    {
        return $this->questionResponses;
    }

    public function addQuestionResponse(QuestionResponse $questionResponse): static
    {
        if (!$this->questionResponses->contains($questionResponse)) {
            $this->questionResponses->add($questionResponse);
            $questionResponse->setQuestion($this);
        }

        return $this;
    }

    public function removeQuestionResponse(QuestionResponse $questionResponse): static
    {
        if ($this->questionResponses->removeElement($questionResponse)) {
            // set the owning side to null (unless already changed)
            if ($questionResponse->getQuestion() === $this) {
                $questionResponse->setQuestion(null);
            }
        }

        return $this;
    }
}
