<?php

declare(strict_types=1);


namespace DMP\AuditRequestBundle\Infrastructure\Middleware;

use Doctrine\Common\Annotations\Reader;
use Symfony\Component\HttpFoundation\Request;

class RequestMiddleware
{
    public function __construct(
        private readonly Reader $reader)
    {
    }

    public function handle(Request $request): void
    {

    }
}
