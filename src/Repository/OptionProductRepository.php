<?php

namespace App\Repository;

use App\Entity\OptionProduct;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OptionProduct>
 */
class OptionProductRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OptionProduct::class);
    }

    /**
     * Fetch available product IDs based on selected option IDs.
     *
     * @param array $optionIds
     * @return array
     */
    public function findAllAvailableProducts(array $optionIds): array
    {
        $optionProducts = $this->createQueryBuilder('op')
            ->innerJoin('op.product', 'p')
            ->addSelect('p')
            ->where('op.is_available = 1')
            ->andWhere('op.option IN (:optionIds)')
            ->setParameter('optionIds', $optionIds)
            ->getQuery()
            ->getResult();

        return array_map(fn($op) => $op->getProduct(), $optionProducts);
    }

    /**
     * Fetch excluded product IDs based on selected option IDs.
     *
     * @param array $optionIds
     * @return array
     */
    public function findAllExcludedProducts(array $optionIds): array
    {
        $optionProducts = $this->createQueryBuilder('op')
            ->join('op.product', 'p')
            ->addSelect('p')
            ->where('op.is_available = 0')
            ->andWhere('op.option IN (:optionIds)')
            ->setParameter('optionIds', $optionIds)
            ->getQuery()
            ->getResult();

        return array_map(fn($op) => $op->getProduct(), $optionProducts);
    }

    /**
     * Fetch recommended products by excluding unavailable products from available products.
     *
     * @param array $optionIds
     * @return array
     */
    public function findAllRecommendedProducts(array $optionIds): array
    {
        $availableProducts = $this->findAllAvailableProducts($optionIds);
        $excludedProducts = $this->findAllExcludedProducts($optionIds);
        $excludedProductIds = array_map(fn($product) => $product->getId(), $excludedProducts);
        return array_filter($availableProducts, fn($product) => !in_array($product->getId(), $excludedProductIds));
    }
}
