<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Domain\Specification;

use DMP\AuditRequestBundle\Domain\Status;

class AuditRequestUpdateStatusSpecification
{
    public function __construct(
        public readonly Status $status,
        public readonly ?array $errors,
    )
    {}

}
