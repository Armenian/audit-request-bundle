<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\Aop;

use CG\Proxy\MethodInterceptorInterface;
use CG\Proxy\MethodInvocation;
use DMP\AuditRequestBundle\Application\Command\CreateAuditRequest;
use DMP\AuditRequestBundle\Domain\AuditRequestId;
use DMP\AuditRequestBundle\Domain\Exception\ValidationException;
use DMP\AuditRequestBundle\Domain\Specification\AuditRequestSpecification;
use DMP\AuditRequestBundle\Domain\Status;
use DMP\AuditRequestBundle\Infrastructure\Annotation\AuditableRequest;
use DMP\CQRS\Infrastructure\Bus\Command\CommandBus;
use Doctrine\Common\Annotations\Reader;
use JsonException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Throwable;
use ReflectionMethod;
use RuntimeException;

final class AuditableRequestInterceptor implements MethodInterceptorInterface
{
    private readonly Request $request;

    public function __construct(
        private readonly Reader $reader,
        private readonly CommandBus $commandBus,
        private readonly Security $security,
        RequestStack $requestStack)
    {
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @throws Throwable
     * @throws ValidationException
     */
    public function intercept(MethodInvocation $invocation): mixed
    {
        $methodDefinition = $invocation->reflection;
        $annotation = $this->getTransactionalAnnotation($methodDefinition);


        try {
            $return = $invocation->proceed();
            $this->createAudit(Status::SUCCESS, null, $annotation->getReferenceType(), $annotation->getReferenceId());

            return $return;
        } catch (Throwable $throwable) {
            if ($throwable instanceof ValidationException) {
                $this->createAudit(Status::VALIDATION_ERROR, $throwable->getConstraintViolationList(), $annotation->getReferenceType(), $annotation->getReferenceId());
            } else {
                $this->createAudit(Status::ERROR, [$throwable->getMessage()], $annotation->getReferenceType(), $annotation->getReferenceId());
            }

            throw $throwable;
        }
    }

    /**
     * @throws JsonException
     */
    private function createAudit(Status $status, ?array $errors, ?string $referenceType, ?string $referenceId): void
    {
        $this->commandBus->handle(
            new CreateAuditRequest(
                AuditRequestId::create(),
                new AuditRequestSpecification(
                    $this->request->getClientIp(),
                    $this->request->getMethod(),
                    json_decode($this->request->getContent(), true, 512, JSON_THROW_ON_ERROR),
                    $status,
                    $errors,
                    $referenceType,
                    $referenceId
                ),
                $this->security->getUser()?->getUserIdentifier()
            )
        );
    }

    private function getTransactionalAnnotation(ReflectionMethod $method)
    {
        $annotation = $this->reader->getMethodAnnotation($method, AuditableRequest::class);
        if ($annotation === null) {
            throw new RuntimeException('AuditableRequest annotation not found.');
        }
        return $annotation;
    }

}
