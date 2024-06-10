<?php

namespace App\Repository;

use App\Entity\Questionnaire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Questionnaire>
 */
class QuestionnaireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Questionnaire::class);
    }

    /**
     * Finds a questionnaire by its ID along with its related questions and options.
     *
     * @param int $id
     * @return Questionnaire|null
     */
    public function findWithRelations(int $id): ?Questionnaire
    {
        return $this->createQueryBuilder('questionnaire')
            ->leftJoin('questionnaire.questions', 'q')
            ->addSelect('q')
            ->leftJoin('q.options', 'o')
            ->addSelect('o')
            ->where('questionnaire.id = :id')
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
