<?php

namespace App\Dto;

use Symfony\Component\Validator\Constraints as Assert;

class AnswerDto
{
    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $question_id;

    #[Assert\NotBlank]
    #[Assert\Type('integer')]
    public int $option_id;

    public function __construct(int $question_id, int $option_id)
    {
        $this->question_id = $question_id;
        $this->option_id = $option_id;
    }

    public function toArray(): array
    {
        return [
            'question_id' => $this->question_id,
            'option_id' => $this->option_id,
        ];
    }
}
