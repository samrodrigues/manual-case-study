<?php

declare(strict_types=1);

namespace App\Controller\Api;

use App\Repository\QuestionnaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\SerializerInterface;

class QuestionnaireController extends AbstractController
{
    public function __construct(private SerializerInterface $serializer)
    {
    }

    #[Route('/questionnaire/{id}', name: 'questionnaire_read', methods: ['GET'])]
    public function getQuestionnaire(int $id, QuestionnaireRepository $questionnaireRepository): JsonResponse
    {
        $questionnaire = $questionnaireRepository->findWithRelations($id);
        $data = $this->serializer->normalize($questionnaire, null, ['groups' => 'questionnaire']);

        return new JsonResponse($data);
    }
}
