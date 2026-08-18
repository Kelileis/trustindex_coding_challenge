<?php

namespace App\Repository;

use App\Entity\Review;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Review>
 */
class ReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Review::class);
    }

    /**
     * @return Review[]
     */
    public function findLatestReviews(int $limit = 20): array
    {
        return $this->createQueryBuilder('r')
            ->orderBy('r.created_at', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * @return Review[]
     */
    public function findByCompanyName(string $companyName): array
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.company_name LIKE :companyName')
            ->setParameter('companyName', '%'.$companyName.'%')
            ->orderBy('r.created_at', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @return array<int, array{company_name: string, review_count: int, avg_rating: float}>
     */
    public function getCompanyStatistics(): array
    {
        $result = $this->createQueryBuilder('r')
            ->select('r.company_name')
            ->addSelect('COUNT(r.id) as review_count')
            ->addSelect('AVG(r.rating) as avg_rating')
            ->groupBy('r.company_name')
            ->orderBy('avg_rating', 'DESC')
            ->getQuery()
            ->getResult();

        return array_map(fn ($row) => [
            'company_name' => $row['company_name'],
            'review_count' => (int) $row['review_count'],
            'avg_rating' => round((float) $row['avg_rating'], 1),
        ], $result);
    }
}
