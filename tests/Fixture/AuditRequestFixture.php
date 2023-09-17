<?php

declare(strict_types=1);


namespace DMP\AuditRequestBundle\Tests\Fixture;

use DMP\AuditRequestBundle\Domain\Status;

class AuditRequestFixture
{
    public const AUDIT_REQUEST_1_ID = '86152b7f-14a8-4e5b-827c-c01ac4b8c8bc';
    public const AUDIT_REQUEST_1_IP = '127.1.1.1';
    public const AUDIT_REQUEST_1_METHOD = 'POST';
    public const AUDIT_REQUEST_1_REQUEST = ['a' => 'b'];
    public const AUDIT_REQUEST_1_STATUS = Status::SUCCESS;
    public const AUDIT_REQUEST_1_ERRORS = null;
    public const AUDIT_REQUEST_1_REFERENCE_TYPE = 'reference';
    public const AUDIT_REQUEST_1_REFERENCE_ID = '1';
}
