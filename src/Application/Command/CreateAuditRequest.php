<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Application\Command;

use DMP\AuditRequestBundle\Domain\AuditRequestId;
use DMP\AuditRequestBundle\Domain\Specification\AuditRequestSpecification;
use DMP\CQRS\Application\Command\CommandInterface;

final class CreateAuditRequest implements CommandInterface
{
    public function __construct(
        private readonly AuditRequestId $auditRequestId,
        private readonly AuditRequestSpecification $specification,
        private readonly string $userId
    ) {
    }

    public function getAuditRequestId(): AuditRequestId
    {
        return $this->auditRequestId;
    }

    public function getSpecification(): AuditRequestSpecification
    {
        return $this->specification;
    }

    public function getUserId(): string
    {
        return $this->userId;
    }
}
