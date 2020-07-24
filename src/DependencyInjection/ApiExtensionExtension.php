<?php


namespace Cs\ApiExtensionBundle\DependencyInjection;

use Cs\ApiExtensionBundle\Api\Controller\ApiControllerInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

/**
 * Class ApiExtensionExtension
 * @package Cs\ApiExtensionBundle\DependencyInjection
 */
class ApiExtensionExtension extends Extension
{
    /**
     * @inheritDoc
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__.'/../Resources/config')
        );
        $loader->load('services.yaml');
        $this->registerForAutoConfiguration($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function registerForAutoConfiguration(ContainerBuilder $container): void
    {
        $container->registerForAutoconfiguration(ApiControllerInterface::class)
            ->addTag('api-extension.api.controller')
            ->addTag('controller.service_arguments');
    }
}