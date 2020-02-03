<?php
namespace TwbBundle;
class Module implements
	\Laminas\ModuleManager\Feature\ConfigProviderInterface,
	\Laminas\ModuleManager\Feature\AutoloaderProviderInterface{

	/**
	 * @see \Laminas\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
	 * @return array
	 */
	public function getAutoloaderConfig(){
        return array(
            'Laminas\Loader\ClassMapAutoloader' => array(
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