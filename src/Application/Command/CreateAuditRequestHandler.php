<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Application\Command;

use DMP\AuditRequestBundle\Domain\AuditRequest;
use DMP\AuditRequestBundle\Domain\AuditRequestRepository;
use DMP\CQRS\Application\Command\CommandHandlerInterface;

class CreateAuditRequestHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly AuditRequestRepository $repository
    )
    {}

    public function __invoke(CreateAuditRequest $command): void
    {
        $this->repository->save(
            new AuditRequest(
                $command->getAuditRequestId(),
                $command->getSpecification(),
                $command->getUserId()
            )
        );
        $this->repository->commit();
    }
}
