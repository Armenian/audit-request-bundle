<?php

declare(strict_types=1);


namespace DMP\AuditRequestBundle\Domain\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

interface ValidationException
{
    public function getConstraintViolationList(): ConstraintViolationListInterface;
}
