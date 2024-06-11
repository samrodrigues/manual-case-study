<?php

namespace App\Repository;

use App\Entity\QuestionnaireSubmission;
use App\Entity\QuestionResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<QuestionnaireSubmission>
 */
class QuestionnaireSubmissionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, QuestionnaireSubmission::class);
    }

    public function saveSubmission(QuestionnaireSubmission $submission, array $answers): void
    {
        $entityManager = $this->getEntityManager();

        foreach ($answers as $answer) {
            $questionResponse = new QuestionResponse();
            $questionResponse->setQuestion($entityManager->getReference('App\Entity\Question', $answer->question_id));
            $questionResponse->setOption($entityManager->getReference('App\Entity\Option', $answer->option_id));
            $questionResponse->setQuestionnaireSubmission($submission);
            $entityManager->persist($questionResponse);
        }

        $entityManager->persist($submission);
        $entityManager->flush();
    }
}
