<?php
namespace TwbBundle\Options\Factory;

use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;
use TwbBundle\Options\ModuleOptions;
use Interop\Container\ContainerInterface;

class ModuleOptionsFactory implements FactoryInterface
{

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('config');
        $options = $config['twbbundle'];
        return new ModuleOptions($options);
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $options = $config['twbbundle'];
        return new ModuleOptions($options);
    }
}
