<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Infrastructure\Doctrine;

use DMP\AuditRequestBundle\Domain\AuditRequestId;

class AuditRequestIdType extends UidType
{
    public const NAME = 'audit_request_id';

    public function getName(): string
    {
        return self::NAME;
    }

    protected function getTypeClass(): string
    {
        return AuditRequestId::class;
    }
}
