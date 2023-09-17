<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Application\Command;

use DMP\AuditRequestBundle\Domain\AuditRequestId;
use DMP\AuditRequestBundle\Domain\Specification\AuditRequestUpdateStatusSpecification;
use DMP\CQRS\Application\Command\CommandInterface;

final class UpdateAuditRequest implements CommandInterface
{
    public function __construct(
        private readonly AuditRequestId $auditRequestId,
        private readonly AuditRequestUpdateStatusSpecification $specification
    ) {
    }

    public function getAuditRequestId(): AuditRequestId
    {
        return $this->auditRequestId;
    }

    public function getSpecification(): AuditRequestUpdateStatusSpecification
    {
        return $this->specification;
    }
}
