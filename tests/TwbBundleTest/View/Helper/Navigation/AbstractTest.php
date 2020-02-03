<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace TwbBundleTest\View\Helper\Navigation;

use PHPUnit\Framework\TestCase;
use Laminas\Navigation\Navigation;
use Laminas\Config\Factory as ConfigFactory;
use Laminas\Mvc\Router\RouteMatch as V2RouteMatch;
use Laminas\Mvc\Service\ServiceManagerConfig;
use Laminas\Navigation\Service\DefaultNavigationFactory;
use Laminas\Router\ConfigProvider as RouterConfigProvider;
use Laminas\Router\RouteMatch as V3RouteMatch;
use Laminas\ServiceManager\Config;
use Laminas\ServiceManager\ServiceManager;
use Laminas\View\Renderer\PhpRenderer;

/**
 * Base class for navigation view helper tests
 */
abstract class AbstractTest extends TestCase
{
    /**
     * @var ServiceManager
     */
    protected $serviceManager;

    // @codingStandardsIgnoreStart
    /**
     * Path to files needed for test
     *
     * @var string
     */
    protected $_files;

    /**
     * Class name for view helper to test
     *
     * @var string
     */
    protected $_helperName;

    /**
     * View helper
     *
     * @var \Laminas\View\Helper\Navigation\AbstractHelper
     */
    protected $_helper;

    /**
     * The first container in the config file (files/navigation.xml)
     *
     * @var Navigation
     */
    protected $_nav;

    private $_oldControllerDir;
    // @codingStandardsIgnoreEnd

    /**
     * Prepares the environment before running a test
     *
     */
    protected function setUp()
    {
        $cwd = __DIR__;

        $this->routeMatchType = class_exists(V2RouteMatch::class)
            ? V2RouteMatch::class
            : V3RouteMatch::class;

        // read navigation config
        $this->_files = $cwd . '/_files';
        $config = ConfigFactory::fromFile($this->_files . '/navigation.xml', true);

        // setup containers from config
        $this->_nav = new Navigation($config->get('nav_test1'));

        // setup view
        $view = new PhpRenderer();
        $view->resolver()->addPath($cwd . '/_files/mvc/views');

        // create helper
        $this->_helper = new $this->_helperName;
        $this->_helper->setView($view);

        // set nav1 in helper as default
        $this->_helper->setContainer($this->_nav);

        // setup service manager
        $smConfig = [
            'modules'                 => [],
            'module_listener_options' => [
                'config_cache_enabled' => false,
                'cache_dir'            => 'data/cache',
                'module_paths'         => [],
                'extra_config'         => [
                    'service_manager' => [
                        'factories' => [
                            'config' => function () use ($config) {
                                return [
                                    'navigation' => [
                                        'default' => $config->get('nav_test'),
                                    ],
                                ];
                            }
                        ],
                    ],
                ],
            ],
        ];

        $sm = $this->serviceManager = new ServiceManager();
        $sm->setAllowOverride(true);

        (new ServiceManagerConfig())->configureServiceManager($sm);

        if (! class_exists(V2RouteMatch::class) && class_exists(RouterConfigProvider::class)) {
            $routerConfig = new Config((new RouterConfigProvider())->getDependencyConfig());
            $routerConfig->configureServiceManager($sm);
        }

        $sm->setService('ApplicationConfig', $smConfig);
        $sm->get('ModuleManager')->loadModules();
        $sm->get('Application')->bootstrap();
        $sm->setFactory('Navigation', DefaultNavigationFactory::class);

        $sm->setService('nav', $this->_nav);

        $sm->setAllowOverride(false);

        $app = $this->serviceManager->get('Application');
        $app->getMvcEvent()->setRouteMatch(new $this->routeMatchType([
            'controller' => 'post',
            'action'     => 'view',
            'id'         => '1337',
        ]));
    }

    /**
     * Returns the contens of the expected $file
     * @param  string $file
     * @return string
     */
    // @codingStandardsIgnoreStart
    protected function _getExpected($file)
    {
        // @codingStandardsIgnoreEnd
        return file_get_contents($this->_files . '/expected/' . $file);
    }

}
