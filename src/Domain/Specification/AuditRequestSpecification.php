<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Domain\Specification;

use DMP\AuditRequestBundle\Domain\Status;

class AuditRequestSpecification
{
    public function __construct(
        public readonly string $ip,
        public readonly string $method,
        public readonly array $request,
        public readonly Status $status,
        public readonly ?array $errors,
        public readonly ?string $referenceType,
        public readonly ?string $referenceId
    )
    {}

}
