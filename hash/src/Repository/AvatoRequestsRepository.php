<?php

namespace App\Repository;

use App\Entity\AvatoRequests;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AvatoRequests>
 *
 * @method AvatoRequests|null find($id, $lockMode = null, $lockVersion = null)
 * @method AvatoRequests|null findOneBy(array $criteria, array $orderBy = null)
 * @method AvatoRequests[]    findAll()
 * @method AvatoRequests[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AvatoRequestsRepository extends AbstractRepository
{
    public function getEntityClassName(): string
    {
        return AvatoRequests::class;
    }

    public function search(array $filters, int $limit, int $offset)
    {
        $qb = $this->createQueryBuilder('a')
        ->select('a.momentRequest, a.requestNumber, a.inputString, a.keyFound, a.alias');

        if ($filters['lessThanAttempts'] ?? null) {
            $qb->andWhere('a.attempts < :lessThanAttempts')
            ->setParameter('lessThanAttempts', $filters['lessThanAttempts']);
        }

        if ($filters['attempts'] ?? null) {
            $qb->andWhere('a.attempts = :attempts')
            ->setParameter('attempts', $filters['attempts']);
        }

        if ($filters['alias'] ?? null) {
            $qb->andWhere('a.alias = :alias')
            ->setParameter('alias', $filters['alias']);
        }

        $qb->setFirstResult($offset)
            ->setMaxResults($limit);

        return $qb->getQuery()->getResult();

    }
}
