<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Tests;

class FakeUser
{
    public static function fixedUser(): self
    {
        return new self('89315fc1-cd2a-4bc2-b811-e4e34127ea30');
    }

    public function __construct(
        private readonly string $id
    )
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

}
