<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Aop;

use DMP\AopBundle\Aop\PointcutInterface;
use DMP\AuditRequestBundle\Infrastructure\Annotation\AuditableRequest;
use Doctrine\Common\Annotations\Reader;
use ReflectionClass;
use ReflectionMethod;

final class AuditableRequestPointcut implements PointcutInterface
{
    public function __construct(
        private readonly Reader $reader)
    {}

    public function matchesClass(ReflectionClass $class): bool
    {
        return false;
    }

    public function matchesMethod(ReflectionMethod $method): bool
    {
        return null !== $this->reader->getMethodAnnotation($method, AuditableRequest::class);
    }
}
