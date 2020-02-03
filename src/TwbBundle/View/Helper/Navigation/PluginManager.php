<?php

namespace TwbBundle\View\Helper\Navigation;


use Laminas\ServiceManager\Factory\InvokableFactory;

class PluginManager extends \Laminas\View\Helper\Navigation\PluginManager
{
    public function __construct($configOrContainerInstance = null, array $v3config = [])
    {
        $this->aliases['twbmenu'] = TwbBundleMenu::class;
        $this->aliases['twbMenu'] = TwbBundleMenu::class;

        $this->factories[TwbBundleMenu::class] = InvokableFactory::class;

        parent::__construct($configOrContainerInstance, $v3config);
    }
}