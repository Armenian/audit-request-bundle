<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Infrastructure\EventListener;

use DMP\AuditRequestBundle\Application\Command\CreateAuditRequest;
use DMP\AuditRequestBundle\Application\Command\UpdateAuditRequest;
use DMP\AuditRequestBundle\Domain\AuditRequestId;
use DMP\AuditRequestBundle\Domain\Exception\ValidationException;
use DMP\AuditRequestBundle\Domain\Specification\AuditRequestSpecification;
use DMP\AuditRequestBundle\Domain\Specification\AuditRequestUpdateStatusSpecification;
use DMP\AuditRequestBundle\Domain\Status;
use DMP\AuditRequestBundle\Infrastructure\Annotation\AuditableRequest;
use DMP\CQRS\Infrastructure\Bus\Command\CommandBus;
use InvalidArgumentException;
use JsonException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Throwable;

class AuditRequestListener implements EventSubscriberInterface
{
    public function __construct(
        private readonly CommandBus $commandBus,
        private readonly Security $security)
    {
    }

    /**
     * @throws JsonException
     */
    public function onKernelController(ControllerEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        /** @var AuditableRequest[] $attributes */
        $attributes = array_values(array_filter($event->getAttributes(), static function($attribute) {
            return $attribute === AuditableRequest::class;
        }, ARRAY_FILTER_USE_KEY));
        if (!empty($attributes)) {
            $request = $event->getRequest();
            $routeParams = $request->attributes->get('_route_params');
            $auditRequestIds = [];
            /** @var AuditableRequest $auditable */
            foreach (reset($attributes) as $auditable)  {
                $auditRequestId = AuditRequestId::create();
                $this->commandBus->handle(
                    new CreateAuditRequest(
                        $auditRequestId,
                        new AuditRequestSpecification(
                            $request->getClientIp(),
                            $auditable->getMethod() ?? $request->getMethod(),
                            $request->getContent() ? json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR) : [],
                            Status::PENDING,
                            null,
                            $auditable->getReferenceType() !== null ? (array_key_exists($auditable->getReferenceType(), $routeParams) ? $routeParams[$auditable->getReferenceType()] : $auditable->getReferenceType()) : null,
                            $auditable->getReferenceIdentifier() !== null ? (array_key_exists($auditable->getReferenceIdentifier(), $routeParams) ? $routeParams[$auditable->getReferenceIdentifier()] : null) : null,
                        ),
                        $this->security->getUser() !== null ? (string)$this->security->getUser()->getId() : null,
                    )
                );
                $auditRequestIds[] = $auditRequestId;
            }
            if (!empty($auditRequestIds)) {
                $event->getRequest()->attributes->set('audit_request_ids', $auditRequestIds);
            }
        }
    }

    public function onKernelResponse(ResponseEvent $event): void
    {
        if (empty($auditRequestIds = $event->getRequest()->attributes->get('audit_request_ids'))) {
            return;
        }
        /** @var AuditRequestId $auditRequestId */
        foreach ($auditRequestIds  as $auditRequestId) {
            $this->commandBus->handle(
                new UpdateAuditRequest(
                    $auditRequestId,
                    new AuditRequestUpdateStatusSpecification(
                        Status::SUCCESS,
                        null
                    )
                )
            );
        }
        $event->getRequest()->attributes->remove('audit_request_ids');
    }

    public function onKernelException(ExceptionEvent $event): void
    {
        if (empty($auditRequestIds = $event->getRequest()->attributes->get('audit_request_ids'))) {
            return;
        }
        $exception = $event->getThrowable();

        /** @var AuditRequestId $auditRequestId */
        foreach ($auditRequestIds  as $auditRequestId) {
            if ($exception instanceof ValidationException) {
                $specification = new AuditRequestUpdateStatusSpecification(
                    Status::VALIDATION_ERROR,
                    $this->getValidationErrors($exception->getConstraintViolationList()),
                );
            } else {
                $specification = new AuditRequestUpdateStatusSpecification(
                    Status::ERROR,
                    [$exception->getMessage()],
                );
            }
            $this->commandBus->handle(
                new UpdateAuditRequest(
                    $auditRequestId,
                    $specification
                )
            );
        }
        $event->getRequest()->attributes->remove('audit_request_ids');
    }

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::CONTROLLER => ['onKernelController', -100],
            KernelEvents::RESPONSE => ['onKernelResponse', 13],
            KernelEvents::EXCEPTION => ['onKernelException', 15],
        ];
    }

    private function getValidationErrors(ConstraintViolationListInterface $violationList): array
    {
        $output = [];
        foreach ($violationList as $violation) {
            $output[$violation->getPropertyPath()][] = $violation->getMessage();
        }
        return $output;
    }
}
