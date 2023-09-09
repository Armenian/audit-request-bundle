<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Application;

use DateTimeImmutable;
use DMP\AuditRequestBundle\Domain\Status;
use Symfony\Component\Validator\Constraints as Assert;

final class AuditRequestCriteria
{
    public function __construct(
        public readonly ?string $userId,
        #[Assert\Choice(callback: [Status::class, 'values'])]
        public readonly ?string $status,
        public readonly ?string $referenceType,
        public readonly ?string $referenceId,
        public readonly ?DateTimeImmutable $dateFrom,
        public readonly ?DateTimeImmutable $dateTo,
    )
    {}
}
