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
    private readonly ?string $referenceType;
    private readonly ?string $referenceId;

    public function __construct(array $options = [])
    {
        if (isset($options['referenceType'])) {
            $this->referenceType = $options['referenceType'];
        }
        if (isset($options['referenceId'])) {
            $this->referenceId = $options['referenceId'];
        }
    }

    public function getReferenceType(): ?string
    {
        return $this->referenceType;
    }

    public function getReferenceId(): ?string
    {
        return $this->referenceId;
    }
}
