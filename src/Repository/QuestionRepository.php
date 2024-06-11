<?php

namespace App\Repository;

use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Question>
 */
class QuestionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Question::class);
    }

    /**
     * Identify conflicting sets of questions based on next question IDs.
     *
     * @param int $questionnaireId
     * @return array
     */
    public function identifyConflictingSets(int $questionnaireId): array
    {
        $qb = $this->createQueryBuilder('q')
            ->select('q.id, IDENTITY(q.parentQuestion) AS parentQuestionId')
            ->where('q.questionnaire = :questionnaireId')
            ->setParameter('questionnaireId', $questionnaireId);

        $results = $qb->getQuery()->getArrayResult();

        $conflictingSets = [];
        foreach ($results as $result) {
            if ($result['parentQuestionId'] !== null) {
                $conflictingSets[$result['parentQuestionId']][] = $result['id'];
            }
        }

        return $conflictingSets;
    }
}
