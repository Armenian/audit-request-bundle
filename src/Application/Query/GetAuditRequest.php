<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Application\Query;

use DMP\AuditRequestBundle\Domain\AuditRequestId;
use DMP\CQRS\Application\Query\QueryInterface;

class GetAuditRequest implements QueryInterface
{
    public function __construct(
        private readonly AuditRequestId $auditRequestId
    ) {
    }

    public function getAuditRequestId(): AuditRequestId
    {
        return $this->auditRequestId;
    }
}
