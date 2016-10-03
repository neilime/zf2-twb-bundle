<?php
namespace TwbBundle\Form\View\Helper\Factory;

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

    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, 'TwbBundle\Form\View\Helper\TwbBundleFormElement');
    }

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = $container->get('TwbBundle\Options\ModuleOptions');
        return new TwbBundleFormElement($options);
    }
}
