<?php
namespace TwbBundle\View\Helper\Navigation;

use Zend\ServiceManager\ConfigInterface;
use Zend\ServiceManager\ServiceManager;

/**
 * Class PluginConfigurator
 * @package TwbBundle\View\Helper\Navigation
 */
class PluginConfigurator implements ConfigInterface
{
    /**
     * @var array Nav View helpers
     */
    protected $helpers = [
        'twbNavbar'     => 'TwbBundle\View\Helper\Navigation\TwbNavbar',
        'twbNavList'    => 'TwbBundle\View\Helper\Navigation\TwbNavList',
        'twbTabs'       => 'TwbBundle\View\Helper\Navigation\TwbTabs',
        'twbButtons'    => 'TwbBundle\View\Helper\Navigation\TwbButtons',
    ];

    public function configureServiceManager(ServiceManager $serviceManager)
    {
        foreach($this->helpers as $name => $fqcn) {
            $serviceManager->setInvokableClass($name, $fqcn);
        }
    }
}
