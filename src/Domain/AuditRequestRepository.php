<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Domain;

use DMP\AuditRequestBundle\Application\AuditRequestCriteria;
use DMP\PaginatorBundle\Pagination\Paginated;
use DMP\PaginatorBundle\Pagination\Pagination;
use Doctrine\Common\Collections\Collection;
use Doctrine\Persistence\ObjectRepository;

/**
 * @method AuditRequest|null        find($id, $lockMode = null, $lockVersion = null)
 * @method AuditRequest|null        findOneBy(array $criteria, ?array $orderBy = null)
 * @method Collection<AuditRequest> findAll()
 * @method Collection<AuditRequest> findBy(array $criteria, ?array $orderBy = null, $limit = null, $offset = null)
 */
interface AuditRequestRepository extends ObjectRepository
{
    public function paginate(Pagination $pagination, AuditRequestCriteria $criteria, ?callable $callback = null): Paginated;
    public function save(AuditRequest $auditRequest): void;

    public function commit(): void;
}
