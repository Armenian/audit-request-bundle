<?php

declare(strict_types=1);


namespace DMP\AuditRequestBundle\Tests\Application\Command;

use DMP\AuditRequestBundle\Application\Command\UpdateAuditRequest;
use DMP\AuditRequestBundle\Application\Command\UpdateAuditRequestHandler;
use DMP\AuditRequestBundle\Application\Exception\AuditRequestNotFoundException;
use DMP\AuditRequestBundle\Domain\AuditRequest;
use DMP\AuditRequestBundle\Domain\AuditRequestId;
use DMP\AuditRequestBundle\Domain\AuditRequestRepository;
use DMP\AuditRequestBundle\Domain\Specification\AuditRequestUpdateStatusSpecification;
use DMP\AuditRequestBundle\Tests\Fixture\AuditRequestFixture;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class UpdateAuditRequestHandlerTest extends TestCase
{
    private AuditRequestRepository|MockObject $repository;
    private AuditRequest|MockObject $auditRequest;
    private UpdateAuditRequestHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(AuditRequestRepository::class);
        $this->auditRequest = $this->createMock(AuditRequest::class);
        $this->handler = new UpdateAuditRequestHandler($this->repository);
    }

    /**
     * @test
     * @throws AuditRequestNotFoundException
     */
    public function it_update_audit_request(): void
    {
        $auditRequestId = AuditRequestId::fromString(AuditRequestFixture::AUDIT_REQUEST_1_ID);
        $specification = new AuditRequestUpdateStatusSpecification(
            AuditRequestFixture::AUDIT_REQUEST_1_STATUS,
            AuditRequestFixture::AUDIT_REQUEST_1_ERRORS,
        );

        $this->repository->expects(self::once())
            ->method('find')
            ->with($auditRequestId->toString())
            ->willReturn($this->auditRequest);

        $this->repository->expects(self::once())->method('save');
        $this->repository->expects(self::once())->method('commit');

        $handler = $this->handler;
        $handler(new UpdateAuditRequest(
            $auditRequestId,
            $specification
        ));
    }

    /**
     * @test
     */
    public function it_fails_on_update_audit_request_with_not_found_exception(): void
    {
        $auditRequestId = AuditRequestId::fromString('ceef7ad4-4115-4ddc-99c2-87b6e7217376');
        $specification = new AuditRequestUpdateStatusSpecification(
            AuditRequestFixture::AUDIT_REQUEST_1_STATUS,
            AuditRequestFixture::AUDIT_REQUEST_1_ERRORS,
        );

        $this->repository->expects(self::once())
            ->method('find')
            ->with($auditRequestId->toString())
            ->willReturn(null);

        $this->expectException(AuditRequestNotFoundException::class);

        $handler = $this->handler;
        $handler(new UpdateAuditRequest(
            $auditRequestId,
            $specification
        ));
    }
}
