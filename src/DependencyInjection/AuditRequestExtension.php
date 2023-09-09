<?php

declare(strict_types=1);

namespace DMP\AuditRequestBundle\DependencyInjection;

use Exception;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader as ConfigYamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class AuditRequestExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @throws Exception
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../../config'));
        $loader->load('services.yaml');
    }

    /**
     * @throws Exception
     */
    public function prepend(ContainerBuilder $container): void
    {
        $loader = new ConfigYamlFileLoader($container, new FileLocator(__DIR__ . '/../../config'));
        $loader->load('prepend.yaml');
    }
}
