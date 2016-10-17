<?php

namespace TwbBundle;

class Module
{

	/**
	 * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
	 * @return array
	 */
	public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                realpath(__DIR__.'/../../autoload_classmap.php')
            )
        );
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__.'/../../config/module.config.php';
    }
}
