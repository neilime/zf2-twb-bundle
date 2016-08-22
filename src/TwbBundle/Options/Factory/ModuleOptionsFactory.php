<?php

namespace TwbBundle\Options\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use TwbBundle\Options\ModuleOptions;
use Interop\Container\ContainerInterface;

class ModuleOptionsFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $options = $config['twbbundle'];
        return new ModuleOptions($options);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = NULL)
    {
        $config = $container->get('Config');
        $options = $config['twbbundle'];
        return new ModuleOptions($options);
    }
}
