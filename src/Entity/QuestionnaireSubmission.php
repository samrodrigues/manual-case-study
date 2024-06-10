<?php

namespace App\Entity;

use App\Repository\QuestionnaireSubmissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionnaireSubmissionRepository::class)]
class QuestionnaireSubmission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'questionnaireSubmissions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Respondent $respondent = null;

    #[ORM\ManyToOne(inversedBy: 'questionnaireSubmissions')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Questionnaire $questionnaire = null;

    /**
     * @var Collection<int, QuestionResponse>
     */
    #[ORM\OneToMany(targetEntity: QuestionResponse::class, mappedBy: 'questionnaire_submission', orphanRemoval: true)]
    private Collection $questionResponses;

    public function __construct()
    {
        $this->questionResponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRespondent(): ?Respondent
    {
        return $this->respondent;
    }

    public function setRespondent(?Respondent $respondent): static
    {
        $this->respondent = $respondent;

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
            $questionResponse->setQuestionnaireSubmission($this);
        }

        return $this;
    }

    public function removeQuestionResponse(QuestionResponse $questionResponse): static
    {
        if ($this->questionResponses->removeElement($questionResponse)) {
            // set the owning side to null (unless already changed)
            if ($questionResponse->getQuestionnaireSubmission() === $this) {
                $questionResponse->setQuestionnaireSubmission(null);
            }
        }

        return $this;
    }
}
