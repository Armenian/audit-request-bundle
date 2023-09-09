<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Application\Query;

use DMP\AuditRequestBundle\Application\Dto\AuditRequestDto;
use DMP\AuditRequestBundle\Domain\AuditRequestRepository;
use DMP\CQRS\Application\Query\QueryHandlerInterface;
use DMP\PaginatorBundle\Pagination\Paginated;

class GetAuditRequestListHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly AuditRequestRepository $repository
    ) {
    }

    public function __invoke(GetAuditRequestList $query): Paginated
    {
        return $this->repository->paginate(
            $query->getPagination(),
            $query->getCriteria(),
            [AuditRequestDto::class, 'fromDomain']
        );
    }
}
