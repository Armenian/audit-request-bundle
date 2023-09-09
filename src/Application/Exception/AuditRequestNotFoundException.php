<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Application\Exception;

use Exception;
use function sprintf;

final class AuditRequestNotFoundException extends Exception
{
    public function __construct(private readonly string $id)
    {
        parent::__construct(
            sprintf('AuditRequest with id [%s] not found', $id),
            404
        );
    }
}
