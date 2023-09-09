<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Domain;

use JsonSerializable;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Uid\Uuid;

final class AuditRequestId implements JsonSerializable
{
    #[ORM\Column(type: 'uuid')]
    #[ORM\Id]
    private readonly Uuid $id;

    public function __construct(?Uuid $id = null)
    {
        if ($id) {
            $this->id = $id;
        } else {
            $this->id = Uuid::v6();
        }
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public static function create(): AuditRequestId
    {
        return new AuditRequestId(Uuid::v6());
    }

    public static function fromString(string $id): AuditRequestId
    {
        return new AuditRequestId(new Uuid($id));
    }

    public function __toString(): string
    {
        return $this->id->toRfc4122();
    }

    public function toString(): string
    {
        return $this->id->toRfc4122();
    }

    public function jsonSerialize(): string
    {
        return $this->id->toRfc4122();
    }

}
