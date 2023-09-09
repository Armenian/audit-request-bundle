<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Application\Query;

use DMP\AuditRequestBundle\Application\Dto\AuditRequestDto;
use DMP\AuditRequestBundle\Application\Exception\AuditRequestNotFoundException;
use DMP\AuditRequestBundle\Domain\AuditRequestRepository;
use DMP\CQRS\Application\Query\QueryHandlerInterface;

class GetAuditRequestHandler implements QueryHandlerInterface
{
    public function __construct(
        private readonly AuditRequestRepository $repository
    ) {
    }

    /**
     * @throws AuditRequestNotFoundException
     */
    public function __invoke(GetAuditRequest $query): AuditRequestDto
    {
        $audit = $this->repository->find($query->getAuditRequestId());
        if ($audit === null) {
            throw new AuditRequestNotFoundException($query->getAuditRequestId()->toString());
        }

        return AuditRequestDto::fromDomain($audit);
    }
}
