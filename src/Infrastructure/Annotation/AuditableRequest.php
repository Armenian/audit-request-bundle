<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Infrastructure\Annotation;


/**
 * @Annotation
 * @Target({"CLASS"})
 */
#[\Attribute(\Attribute::IS_REPEATABLE | \Attribute::TARGET_METHOD)]
class AuditableRequest
{

    public function __construct(
        private readonly ?string $referenceType = null,
        private readonly ?string $referenceIdentifier = null,
        private readonly ?string $method = null)
    {}

    public function getReferenceType(): ?string
    {
        return $this->referenceType;
    }

    public function getReferenceIdentifier(): ?string
    {
        return $this->referenceIdentifier;
    }

    public function getMethod(): ?string
    {
        return $this->method;
    }

}
