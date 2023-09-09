<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Application\Dto;

use DateTimeImmutable;
use DMP\AuditRequestBundle\Domain\AuditRequest;
use DMP\AuditRequestBundle\Domain\Status;

final class AuditRequestDto
{
    public static function fromDomain(AuditRequest $auditRequest): AuditRequestDto
    {
        return new AuditRequestDto(
            $auditRequest->getId()->toString(),
            $auditRequest->getCreatedAt(),
            $auditRequest->getIp(),
            $auditRequest->getMethod(),
            $auditRequest->getRequest(),
            $auditRequest->getStatus(),
            $auditRequest->getErrors(),
            $auditRequest->getReferenceType(),
            $auditRequest->getReferenceId(),
            $auditRequest->getUserId()
        );
    }

    public function __construct(
        public readonly string $id,
        public readonly DateTimeImmutable $createdAt,
        public readonly string $ip,
        public readonly string $method,
        public readonly array $request,
        public readonly Status $status,
        public readonly ?array $errors,
        public readonly ?string $referenceType,
        public readonly ?string $referenceId,
        public readonly string $userId,
    )
    {}
}
