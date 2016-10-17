<?php
namespace TwbBundle;
class Module implements
	\Zend\ModuleManager\Feature\ConfigProviderInterface,
	\Zend\ModuleManager\Feature\AutoloaderProviderInterface{

	/**
	 * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
	 * @return array
	 */
	public function getAutoloaderConfig(){
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                realpath(__DIR__.DIRECTORY_SEPARATOR.'/../../autoload_classmap.php')
            )
        );
    }

    /**
     * @return array
     */
    public function getConfig(){
        return include __DIR__.DIRECTORY_SEPARATOR.'/../../config/module.config.php';
    }
}