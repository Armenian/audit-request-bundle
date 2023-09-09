<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Infrastructure\Persistence;

use DMP\AuditRequestBundle\Application\AuditRequestCriteria;
use DMP\AuditRequestBundle\Domain\AuditRequest;
use DMP\AuditRequestBundle\Domain\AuditRequestRepository;
use DMP\PaginatorBundle\Pagination\Paginated;
use DMP\PaginatorBundle\Pagination\Pagination;
use DMP\PaginatorBundle\Pagination\Paginator;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class DoctrineAuditRequestRepository extends ServiceEntityRepository implements AuditRequestRepository
{
    public function __construct(
        ManagerRegistry $registry,
        private readonly Paginator $paginator,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct($registry, AuditRequest::class);
    }

    public function save(AuditRequest $auditRequest): void
    {
        $this->entityManager->persist($auditRequest);
    }

    public function commit(): void
    {
        $this->entityManager->flush();
    }

    public function paginate(Pagination $pagination, AuditRequestCriteria $criteria, ?callable $callback = null): Paginated
    {
        $qb = $this->initQueryBuilder();

        if ($criteria->status !== null) {
            $qb->andWhere('a.status = :status')->setParameter('status', $criteria->status);
        }
        if ($criteria->userId !== null) {
            $qb->andWhere('a.userId = :userId')->setParameter('userId', $criteria->userId);
        }
        if ($criteria->referenceType !== null) {
            $qb->andWhere('a.referenceType = :referenceType')->setParameter('referenceType', $criteria->referenceType);
        }
        if ($criteria->referenceId !== null) {
            $qb->andWhere('a.referenceId = :referenceId')->setParameter('referenceId', $criteria->referenceId);
        }
        if ($criteria->dateFrom !== null) {
            $qb->andWhere('a.createdAt >= :from')
                ->setParameter('from', $criteria->dateFrom->format('Y-m-d 00:00:00'));
        }
        if ($criteria->dateTo !== null) {
            $qb->andWhere('a.createdAt <= :to')
                ->setParameter('to', $criteria->dateTo->format('Y-m-d 23:59:59'));
        }

        return $this->paginator->paginate($qb, $pagination, false, $callback);
    }

    private function initQueryBuilder(): QueryBuilder
    {
        return $this->entityManager->createQueryBuilder()
            ->from(AuditRequest::class, 'a')
            ->select(['a'])
            ->orderBy('a.createdAt', 'DESC');
    }
}
