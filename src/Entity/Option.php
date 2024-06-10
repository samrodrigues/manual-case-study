<?php

namespace App\Entity;

use App\Repository\OptionRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Attribute\Groups;

#[ORM\Entity(repositoryClass: OptionRepository::class)]
class Option
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['questionnaire'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['questionnaire'])]
    private ?string $text = null;

    #[ORM\ManyToOne(inversedBy: 'options')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Question $question = null;

    #[ORM\ManyToOne(targetEntity: Question::class)]
    private ?Question $next_question = null;

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

    public function getQuestion(): ?Question
    {
        return $this->question;
    }

    public function setQuestion(?Question $question): static
    {
        $this->question = $question;

        return $this;
    }

    public function getNextQuestion(): ?Question
    {
        return $this->next_question;
    }

    public function setNextQuestion(?Question $next_question): static
    {
        $this->next_question = $next_question;

        return $this;
    }

    #[Groups(['questionnaire'])]
    public function getNextQuestionId(): ?int
    {
        return $this->next_question ? $this->next_question->getId() : null;
    }
}
