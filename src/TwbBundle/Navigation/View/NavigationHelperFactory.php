<?php

namespace TwbBundle\Navigation\View;


use Interop\Container\ContainerInterface;
use ReflectionProperty;
use TwbBundle\View\Helper\Navigation as NavigationHelper;

class NavigationHelperFactory extends \Laminas\Navigation\View\NavigationHelperFactory
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $helper = new NavigationHelper();
        $helper->setServiceLocator($this->getApplicationServicesFromContainer($container));
        return $helper;
    }

    private function getApplicationServicesFromContainer(ContainerInterface $container)
    {
        // v3
        if (method_exists($container, 'configure')) {
            $r = new ReflectionProperty($container, 'creationContext');
            $r->setAccessible(true);
            return $r->getValue($container) ?: $container;
        }

        // v2
        return $container->getServiceLocator() ?: $container;
    }
}