<?php

namespace App\Tests\Service;

use App\Dto\AnswerDto;
use App\Dto\QuestionnaireSubmissionCreateDto;
use App\Entity\Option;
use App\Entity\Question;
use App\Repository\OptionRepository;
use App\Repository\QuestionRepository;
use App\Service\QuestionnaireSubmissionValidator;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class QuestionnaireSubmissionValidatorTest extends TestCase
{
    private $entityManager;
    private $optionRepository;
    private $questionRepository;
    private $validator;
    private $questionnaireSubmissionValidator;

    protected function setUp(): void
    {
        parent::setUp();
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->optionRepository = $this->createMock(OptionRepository::class);
        $this->questionRepository = $this->createMock(QuestionRepository::class);
        $this->validator = $this->createMock(ValidatorInterface::class);
        $this->questionnaireSubmissionValidator = new QuestionnaireSubmissionValidator(
            $this->entityManager,
            $this->optionRepository,
            $this->questionRepository,
            $this->validator
        );
    }

    public function testValidateThrowsExceptionOnValidationError()
    {
        $submitDto = new QuestionnaireSubmissionCreateDto(1, 1, []);

        $this->expectException(UnprocessableEntityHttpException::class);

        $this->questionnaireSubmissionValidator->validate($submitDto);
    }


    /**
     * @dataProvider conflictingSetsProvider
     */
    public function testValidateConflictingSets($submissionData, $conflictingSets, $expectException)
    {
        $submitDto = new QuestionnaireSubmissionCreateDto(1, 1, $submissionData);

        $this->questionRepository->method('identifyConflictingSets')
            ->willReturn($conflictingSets);

        if ($expectException) {
            $this->expectException(UnprocessableEntityHttpException::class);
            $this->expectExceptionMessage('Conflicting answers for branching questions.');
        } else {
            $this->addToAssertionCount(1);
        }

        $this->questionnaireSubmissionValidator->validateConflictingSets($submitDto);
    }

    public function conflictingSetsProvider(): array
    {
        return [
            'no_conflict' => [
                'submissionData' => [
                    new AnswerDto(1, 1),
                    new AnswerDto(2, 2),
                ],
                'conflictingSets' => [
                    [3, 4]
                ],
                'expectException' => false,
            ],
            'conflict' => [
                'submissionData' => [
                    new AnswerDto(3, 1),
                    new AnswerDto(4, 2),
                ],
                'conflictingSets' => [
                    [3, 4]
                ],
                'expectException' => true,
            ],
            'multiple_conflicts' => [
                'submissionData' => [
                    new AnswerDto(3, 1),
                    new AnswerDto(4, 2),
                    new AnswerDto(5, 3),
                ],
                'conflictingSets' => [
                    [3, 4],
                    [5, 6]
                ],
                'expectException' => true,
            ],
            'branch_answer_no_conflict' => [
                'submissionData' => [
                    new AnswerDto(1, 1),
                    new AnswerDto(3, 5)
                ],
                'conflictingSets' => [
                    [3, 4]
                ],
                'expectException' => false,
            ],
        ];
    }

    /**
     * @dataProvider optionsProvider
     */
    public function testValidateOptions($question, $option, $expectException)
    {
        $submitDto = new QuestionnaireSubmissionCreateDto(1, 1, [
            new AnswerDto(1, 1),
        ]);

        $this->entityManager->method('getReference')
            ->willReturnMap([
                [Question::class, 1, $question],
                [Option::class, 1, $option],
            ]);

        if ($expectException) {
            $this->expectException(UnprocessableEntityHttpException::class);
            $this->expectExceptionMessage('Invalid option for the given question.');
        } else {
            $this->addToAssertionCount(1);
        }

        $this->questionnaireSubmissionValidator->validateOptions($submitDto);
    }

    public function optionsProvider(): array
    {
        $question = new Question();
        $validOption = new Option();
        $question->addOption($validOption);
        $invalidOption = new Option();

        return [
            'valid_option' => [$question, $validOption, false],
            'invalid_option' => [$question, $invalidOption, true],
        ];
    }

    /**
     * @dataProvider terminalOptionProvider
     */
    public function testValidateTerminalOption(array $options, bool $expectException)
    {
        $submitDto = new QuestionnaireSubmissionCreateDto(1, 1, [
            new AnswerDto(1, 1),
        ]);

        $this->optionRepository->method('findBy')->willReturn($options);

        if ($expectException) {
            $this->expectException(UnprocessableEntityHttpException::class);
            $this->expectExceptionMessage('At least one answer must terminate the questionnaire.');
        } else {
            $this->addToAssertionCount(1);
        }

        $this->questionnaireSubmissionValidator->validateTerminalOption($submitDto);
    }

    public function terminalOptionProvider(): array
    {
        $question = new Question();

        $optionWithNextQuestion = $this->createMock(Option::class);
        $optionWithNextQuestion->method('getNextQuestion')->willReturn($question);

        $optionWithoutNextQuestion = $this->createMock(Option::class);
        $optionWithoutNextQuestion->method('getNextQuestion')->willReturn(null);

        return [
            'no_terminal_option' => [
                'options' => [$optionWithNextQuestion],
                'expectException' => true,
            ],
            'one_terminal_option' => [
                'options' => [$optionWithoutNextQuestion],
                'expectException' => false,
            ],
            'multiple_options_with_terminal' => [
                'options' => [$optionWithNextQuestion, $optionWithoutNextQuestion],
                'expectException' => false,
            ],
            'multiple_options_without_terminal' => [
                'options' => [$optionWithNextQuestion, $optionWithNextQuestion],
                'expectException' => true,
            ],
        ];
    }
}
