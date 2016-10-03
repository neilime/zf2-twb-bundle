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
        return $this($serviceLocator, 'TwbBundle\Options\ModuleOptions');
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $container->get('config');
        $options = $config['twbbundle'];
        return new ModuleOptions($options);
    }
}
