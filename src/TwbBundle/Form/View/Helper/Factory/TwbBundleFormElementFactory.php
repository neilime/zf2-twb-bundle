<?php

namespace TwbBundle\Form\View\Helper\Factory;

use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use TwbBundle\Form\View\Helper\TwbBundleFormElement;
use Interop\Container\ContainerInterface;

/**
 * Factory to inject the ModuleOptions hard dependency
 *
 * @author Fábio Carneiro <fahecs@gmail.com>
 * @license MIT
 */
class TwbBundleFormElementFactory implements FactoryInterface
{

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return TwbBundleFormElement
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator instanceof AbstractPluginManager) {
            $serviceLocator = $serviceLocator->getServiceLocator() ?: $serviceLocator;
        }

        $options = $serviceLocator->get('TwbBundle\Options\ModuleOptions');
        return new TwbBundleFormElement($options);
    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param array|null $options
     * @return TwbBundleFormElement
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = $container->get('TwbBundle\Options\ModuleOptions');
        return new TwbBundleFormElement($options);
    }
}
