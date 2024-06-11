<?php

namespace App\Service;

use App\Dto\QuestionnaireSubmissionCreateDto;
use App\Entity\Option;
use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class QuestionnaireSubmissionValidator
{
    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly QuestionRepository $questionRepository,
        private readonly ValidatorInterface $validator,
    ) {
    }

    public function validate(QuestionnaireSubmissionCreateDto $submitDto): void
    {
        $errors = $this->validator->validate($submitDto);
        if (count($errors) > 0) {
            throw new UnprocessableEntityHttpException((string) $errors);
        }
        $this->validateConflictingSets($submitDto);
        $this->validateOptions($submitDto);
        $this->validateTerminalOption($submitDto);
        // TODO: Validate that all questions, except the first one, have a previous question.
    }

    private function validateConflictingSets(QuestionnaireSubmissionCreateDto $submitDto): void
    {
        $conflictingSets = $this->questionRepository->identifyConflictingSets($submitDto->questionnaire_id);
        $answeredQuestionIds = array_map(fn($answer) => $answer->question_id, $submitDto->answers);

        foreach ($conflictingSets as $set) {
            $intersection = array_intersect($set, $answeredQuestionIds);
            if (count($intersection) > 1) {
                throw new UnprocessableEntityHttpException('Conflicting answers for branching questions.');
            }
        }
    }

    private function validateOptions(QuestionnaireSubmissionCreateDto $submitDto): void
    {
        foreach ($submitDto->answers as $answer) {
            /** @var Question $question */
            $question = $this->entityManager->getReference(Question::class, $answer->question_id);
            /** @var Option $option */
            $option = $this->entityManager->getReference(Option::class, $answer->option_id);

            if (!$question->getOptions()->contains($option)) {
                throw new UnprocessableEntityHttpException('Invalid option for the given question.');
            }
        }
    }

    private function validateTerminalOption(QuestionnaireSubmissionCreateDto $submitDto): void
    {
        $optionIds = array_map(fn($answer) => $answer->option_id, $submitDto->answers);
        $options = $this->entityManager->getRepository(Option::class)->findBy(['id' => $optionIds]);

        $hasTerminalOption = false;
        foreach ($options as $option) {
            if ($option->getNextQuestion() === null) {
                $hasTerminalOption = true;
                break;
            }
        }

        if (!$hasTerminalOption) {
            throw new UnprocessableEntityHttpException('At least one answer must terminate the questionnaire.');
        }
    }
}
