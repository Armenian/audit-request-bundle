<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Application\Command;

use DMP\AuditRequestBundle\Application\Exception\AuditRequestNotFoundException;
use DMP\AuditRequestBundle\Domain\AuditRequestRepository;
use DMP\CQRS\Application\Command\CommandHandlerInterface;

class UpdateAuditRequestHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly AuditRequestRepository $repository
    )
    {}

    /**
     * @throws AuditRequestNotFoundException
     */
    public function __invoke(UpdateAuditRequest $command): void
    {
        $auditRequest = $this->repository->find($command->getAuditRequestId());
        if ($auditRequest === null) {
            throw new AuditRequestNotFoundException($command->getAuditRequestId()->toString());
        }

        $auditRequest->updateStatus($command->getSpecification());
        $this->repository->save($auditRequest);
        $this->repository->commit();
    }
}
