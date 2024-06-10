<?php

namespace App\Entity;

use App\Repository\RespondentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RespondentRepository::class)]
class Respondent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @var Collection<int, QuestionnaireSubmission>
     */
    #[ORM\OneToMany(targetEntity: QuestionnaireSubmission::class, mappedBy: 'respondent', orphanRemoval: true)]
    private Collection $questionnaireSubmissions;

    public function __construct()
    {
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
            $questionnaireSubmission->setRespondent($this);
        }

        return $this;
    }

    public function removeQuestionnaireSubmission(QuestionnaireSubmission $questionnaireSubmission): static
    {
        if ($this->questionnaireSubmissions->removeElement($questionnaireSubmission)) {
            // set the owning side to null (unless already changed)
            if ($questionnaireSubmission->getRespondent() === $this) {
                $questionnaireSubmission->setRespondent(null);
            }
        }

        return $this;
    }
}
