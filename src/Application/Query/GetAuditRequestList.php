<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Application\Query;

use DMP\AuditRequestBundle\Application\AuditRequestCriteria;
use DMP\CQRS\Application\Query\QueryInterface;
use DMP\PaginatorBundle\Pagination\Pagination;


final class GetAuditRequestList implements QueryInterface
{
    public function __construct(
        private readonly Pagination $pagination,
        private readonly AuditRequestCriteria $criteria
    )
    {}

    public function getPagination(): Pagination
    {
        return $this->pagination;
    }

    public function getCriteria(): AuditRequestCriteria
    {
        return $this->criteria;
    }
}
