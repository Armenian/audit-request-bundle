<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Application\Command;

use DMP\AuditRequestBundle\Domain\AuditRequest;
use DMP\AuditRequestBundle\Domain\AuditRequestRepository;
use DMP\CQRS\Application\Command\CommandHandlerInterface;
use DMP\TransactionalBundle\Annotation\Transactional;

class CreateAuditRequestHandler implements CommandHandlerInterface
{
    public function __construct(
        private readonly AuditRequestRepository $accountRepository
    )
    {}

    /**
     * @Transactional()
     */
    public function __invoke(CreateAuditRequest $command): void
    {
        $this->accountRepository->save(
            new AuditRequest(
                $command->getAuditRequestId(),
                $command->getSpecification(),
                $command->getUserId()
            )
        );
    }
}
