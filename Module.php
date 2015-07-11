<?php
namespace TwbBundle;

class Module implements
    \Zend\ModuleManager\Feature\ConfigProviderInterface,
    \Zend\ModuleManager\Feature\AutoloaderProviderInterface
{

    /**
     * @see \Zend\ModuleManager\Feature\AutoloaderProviderInterface::getAutoloaderConfig()
     * @return array
     */
    public function getAutoloaderConfig()
    {
        return [
            'Zend\Loader\ClassMapAutoloader' => [
                __DIR__ . DIRECTORY_SEPARATOR . 'autoload_classmap.php'
            ]
        ];
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return include __DIR__ . DIRECTORY_SEPARATOR . 'config/module.config.php';
    }

    /**
     * OnBootstrap listener
     * @param $e
     */
    public function onBootstrap(\Zend\Mvc\MvcEvent $e)
    {
        $application = $e->getApplication();
        $sm = $application->getServiceManager();
        /* @var $viewHelperPluginManager \Zend\View\HelperPluginManager */
        $viewHelperPluginManager = $sm->get('view_helper_manager');

        /* @var $navViewHelperConfigurator \TwbBundle\View\Helper\Navigation\PluginConfigurator */
        $navViewHelperConfigurator = $sm->get('twb_nav_view_helper_configurator');
        $navHelperPluginManager = $viewHelperPluginManager->get('navigation')->getPluginManager();
        $navViewHelperConfigurator->configureServiceManager($navHelperPluginManager);

        //Prepare the \Zend\Navigation\Page\Mvc for use
        //The pages in navigation container created with a factory have the router injected,
        //but any other explicitly created page needs the router too, so it makes sense to set the default router
        $router = $sm->get('router');
        \Zend\Navigation\Page\Mvc::setDefaultRouter($router);
    }
}