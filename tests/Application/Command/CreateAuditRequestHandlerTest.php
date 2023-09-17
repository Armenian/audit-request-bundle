<?php

declare(strict_types=1);


namespace DMP\AuditRequestBundle\Tests\Application\Command;

use DMP\AuditRequestBundle\Application\Command\CreateAuditRequest;
use DMP\AuditRequestBundle\Application\Command\CreateAuditRequestHandler;
use DMP\AuditRequestBundle\Domain\AuditRequestId;
use DMP\AuditRequestBundle\Domain\AuditRequestRepository;
use DMP\AuditRequestBundle\Domain\Specification\AuditRequestSpecification;
use DMP\AuditRequestBundle\Domain\Status;
use DMP\AuditRequestBundle\Tests\FakeUser;
use DMP\AuditRequestBundle\Tests\Fixture\AuditRequestFixture;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CreateAuditRequestHandlerTest extends TestCase
{
    private AuditRequestRepository|MockObject $repository;
    private CreateAuditRequestHandler $handler;

    protected function setUp(): void
    {
        $this->repository = $this->createMock(AuditRequestRepository::class);
        $this->handler = new CreateAuditRequestHandler($this->repository);
    }

    /**
     * @test
     */
    public function it_create_audit_request(): void
    {
        $auditRequestId = AuditRequestId::fromString(AuditRequestFixture::AUDIT_REQUEST_1_ID);
        $specification = new AuditRequestSpecification(
            AuditRequestFixture::AUDIT_REQUEST_1_IP,
            AuditRequestFixture::AUDIT_REQUEST_1_METHOD,
            AuditRequestFixture::AUDIT_REQUEST_1_REQUEST,
            Status::PENDING,
            AuditRequestFixture::AUDIT_REQUEST_1_ERRORS,
            AuditRequestFixture::AUDIT_REQUEST_1_REFERENCE_TYPE,
            AuditRequestFixture::AUDIT_REQUEST_1_REFERENCE_ID
        );

        $this->repository->expects(self::once())->method('save');
        $this->repository->expects(self::once())->method('commit');

        $handler = $this->handler;
        $handler(new CreateAuditRequest(
            $auditRequestId,
            $specification,
            FakeUser::fixedUser()->getId()
        ));
    }
}
