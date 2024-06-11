<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class QuestionnaireSubmissionCreateDto
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $questionnaire_id;

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $respondent_id;

    #[Assert\NotBlank]
    #[Assert\Valid]
    #[Assert\Count(min: 1)]
    public array $answers = [];

    public function __construct(int $questionnaire_id, int $respondent_id, array $answers)
    {
        $this->questionnaire_id = $questionnaire_id;
        $this->respondent_id = $respondent_id;
        $this->answers = $answers;
    }

    public static function fromArray(array $data): self
    {
        $answers = array_map(
            fn($answer) => new AnswerDto($answer['question_id'], $answer['option_id']),
            $data['answers']
        );

        return new self($data['questionnaire_id'], $data['respondent_id'], $answers);
    }

    public function toArray(): array
    {
        return [
            'questionnaire_id' => $this->questionnaire_id,
            'respondent_id' => $this->respondent_id,
            'answers' => array_map(
                fn($answer) => $answer->toArray(),
                $this->answers
            ),
        ];
    }
}
