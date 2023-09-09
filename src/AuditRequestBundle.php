<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle;

use DMP\AuditRequestBundle\DependencyInjection\AuditRequestExtension;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\HttpKernel\KernelInterface;


class AuditRequestBundle extends Bundle
{
    protected function getContainerExtensionClass(): string
    {
        AnnotationReader::addGlobalIgnoredName('suppress');
        return AuditRequestExtension::class;
    }
}
