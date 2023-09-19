<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Domain;

use DateTimeImmutable;
use DMP\AuditRequestBundle\Domain\Specification\AuditRequestSpecification;
use DMP\AuditRequestBundle\Domain\Specification\AuditRequestUpdateStatusSpecification;
use DMP\AuditRequestBundle\Infrastructure\Doctrine\AuditRequestIdType;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Index(columns: ["created_at"], name: "created_at_idx")]
#[ORM\Index(columns: ["status"], name: "status_idx")]
#[ORM\Index(columns: ["reference_type"], name: "reference_type_idx")]
#[ORM\Index(columns: ["reference_id"], name: "reference_id_idx")]
#[ORM\Index(columns: ["user_id"], name: "user_id_idx")]
#[ORM\ChangeTrackingPolicy('DEFERRED_EXPLICIT')]
class AuditRequest
{
    #[ORM\Id]
    #[ORM\Column(type: AuditRequestIdType::NAME)]
    private readonly AuditRequestId $id;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private readonly DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private readonly ?string $ip;

    #[ORM\Column(type: Types::STRING)]
    private readonly string $method;

    #[ORM\Column(type: Types::JSON, options: ['jsonb' => true])]
    private readonly array $request;

    #[ORM\Column(type: Types::STRING, enumType: Status::class)]
    private Status $status;

    #[ORM\Column(type: Types::JSON, nullable: true, options: ['jsonb' => true])]
    private ?array $errors;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private readonly ?string $referenceType;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private readonly ?string $referenceId;

    #[ORM\Column(type: Types::STRING, nullable: true)]
    private readonly ?string $userId;

    public function __construct(
        AuditRequestId $auditRequestId,
        AuditRequestSpecification $specification,
        ?string $userId
    ) {
        $this->id = $auditRequestId;
        $this->createdAt = new DateTimeImmutable();
        $this->userId = $userId;
        $this->applySpecification($specification);
    }

    public function updateStatus(AuditRequestUpdateStatusSpecification $specification): void
    {
        $this->status = $specification->status;
        $this->errors = $specification->errors;
    }

    public function getId(): AuditRequestId
    {
        return $this->id;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getIp(): string
    {
        return $this->ip;
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getRequest(): array
    {
        return $this->request;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getErrors(): ?array
    {
        return $this->errors;
    }

    public function getReferenceType(): ?string
    {
        return $this->referenceType;
    }

    public function getReferenceId(): ?string
    {
        return $this->referenceId;
    }

    public function getUserId(): ?string
    {
        return $this->userId;
    }

    private function applySpecification(AuditRequestSpecification $specification): void
    {
        $this->ip = $specification->ip;
        $this->method = $specification->method;
        $this->request = $specification->request;
        $this->status = $specification->status;
        $this->errors = $specification->errors;
        $this->referenceType = $specification->referenceType;
        $this->referenceId = $specification->referenceId;
    }
}
