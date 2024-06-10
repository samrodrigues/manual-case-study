<?php

namespace App\Entity;

use App\Repository\QuestionResponseRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionResponseRepository::class)]
class QuestionResponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'questionResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Option $option = null;

    #[ORM\ManyToOne(inversedBy: 'questionResponses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QuestionnaireSubmission $questionnaire_submission = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getOption(): ?Option
    {
        return $this->option;
    }

    public function setOption(?Option $option): static
    {
        $this->option = $option;

        return $this;
    }

    public function getQuestionnaireSubmission(): ?QuestionnaireSubmission
    {
        return $this->questionnaire_submission;
    }

    public function setQuestionnaireSubmission(?QuestionnaireSubmission $questionnaire_submission): static
    {
        $this->questionnaire_submission = $questionnaire_submission;

        return $this;
    }
}
