<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Domain;

enum Status: string
{

    case PENDING = 'pending';
    case SUCCESS = 'success';
    case ERROR = 'error';
    case VALIDATION_ERROR = 'validation_error';
}
