<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Dto\QuestionnaireSubmissionCreateDto;
use App\Entity\QuestionnaireSubmission;
use App\Repository\OptionProductRepository;
use App\Repository\QuestionnaireRepository;
use App\Repository\QuestionnaireSubmissionRepository;
use App\Repository\RespondentRepository;
use App\Service\QuestionnaireSubmissionValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\UnprocessableEntityHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class QuestionnaireSubmissionController extends AbstractController
{
    public function __construct(
        private readonly QuestionnaireRepository $questionnaireRepository,
        private readonly QuestionnaireSubmissionRepository $questionnaireSubmissionRepository,
        private readonly QuestionnaireSubmissionValidator $questionnaireSubmissionValidator,
        private readonly OptionProductRepository $optionProductRepository,
        private readonly RespondentRepository $respondentRepository,
        private readonly SerializerInterface $serializer,
    ) {
    }

    #[Route('/questionnaire-submissions/', name: 'questionnaire_submission_create', methods: ['POST'])]
    public function submitQuestionnaire(
        Request $request,
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);
        $submitDto = QuestionnaireSubmissionCreateDto::fromArray($data);

        // Validate the submitted data
        $this->questionnaireSubmissionValidator->validate($submitDto);

        $questionnaire = $this->questionnaireRepository->find($submitDto->questionnaire_id);
        if (!$questionnaire) {
            throw new UnprocessableEntityHttpException('Invalid questionnaire.');
        }
        $respondent = $this->respondentRepository->find($submitDto->respondent_id);
        if (!$respondent) {
            throw new UnprocessableEntityHttpException('Invalid respondent.');
        }

        // Save the questionnaire submission and responses
        $questionnaireSubmission = new QuestionnaireSubmission();
        $questionnaireSubmission->setQuestionnaire($questionnaire);
        $questionnaireSubmission->setRespondent($respondent);

        try {
            $this->questionnaireSubmissionRepository->saveSubmission($questionnaireSubmission, $submitDto->answers);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'An error occurred while saving the submission.'], 500);
        }

        // Process the answers and determine recommended products
        $optionIds = array_map(fn($answer) => $answer->option_id, $submitDto->answers);
        $recommendedProducts = $this->optionProductRepository->findAllRecommendedProducts($optionIds);

        return new JsonResponse($this->serializer->normalize($recommendedProducts, null, ['groups' => 'product']));
    }
}
