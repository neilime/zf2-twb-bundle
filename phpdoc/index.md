## Table of contents

- [\TwbBundle\Module](#class-twbbundlemodule)
- [\TwbBundle\ConfigProvider](#class-twbbundleconfigprovider)
- [\TwbBundle\Form\View\Helper\Factory\TwbBundleFormElementFactory](#class-twbbundleformviewhelperfactorytwbbundleformelementfactory)
- [\TwbBundle\Navigation\View\NavigationHelperFactory](#class-twbbundlenavigationviewnavigationhelperfactory)
- [\TwbBundle\Options\Factory\ModuleOptionsFactory](#class-twbbundleoptionsfactorymoduleoptionsfactory)
- [\TwbBundle\View\Helper\Navigation](#class-twbbundleviewhelpernavigation)
- [\TwbBundle\View\Helper\Navigation\TwbBundleMenu](#class-twbbundleviewhelpernavigationtwbbundlemenu)
- [\TwbBundle\View\Helper\Navigation\PluginManager](#class-twbbundleviewhelpernavigationpluginmanager)

<hr />

### Class: \TwbBundle\Module

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getAutoloaderConfig()</strong> : <em>array</em> |
| public | <strong>getConfig()</strong> : <em>array</em> |

*This class implements \Zend\ModuleManager\Feature\ConfigProviderInterface, \Zend\ModuleManager\Feature\AutoloaderProviderInterface*

<hr />

### Class: \TwbBundle\ConfigProvider

> The configuration provider for the TwbBundle module

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__invoke()</strong> : <em>array</em><br /><em>Returns the configuration array To add a bit of a structure, each section is defined in a separate method which returns an array with its configuration.</em> |
| protected | <strong>getDependencies()</strong> : <em>array</em><br /><em>Returns dependencies (former server_manager)</em> |
| protected | <strong>getTwbBundleOptions()</strong> : <em>array</em><br /><em>Returns twb bundle options</em> |
| protected | <strong>getViewHelpers()</strong> : <em>array</em><br /><em>Returns view helpers</em> |

<hr />

### Class: \TwbBundle\Form\View\Helper\Factory\TwbBundleFormElementFactory

> Factory to inject the ModuleOptions hard dependency

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__invoke(</strong><em>\Interop\Container\ContainerInterface</em> <strong>$container</strong>, <em>mixed</em> <strong>$requestedName</strong>, <em>array</em> <strong>$options=null</strong>)</strong> : <em>void</em> |
| public | <strong>createService(</strong><em>\Zend\ServiceManager\ServiceLocatorInterface</em> <strong>$serviceLocator</strong>)</strong> : <em>mixed</em> |

*This class implements \Zend\ServiceManager\FactoryInterface, \Zend\ServiceManager\Factory\FactoryInterface*

<hr />

### Class: \TwbBundle\Navigation\View\NavigationHelperFactory

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__invoke(</strong><em>\Interop\Container\ContainerInterface</em> <strong>$container</strong>, <em>mixed</em> <strong>$requestedName</strong>, <em>array</em> <strong>$options=null</strong>)</strong> : <em>void</em> |

*This class extends \Zend\Navigation\View\NavigationHelperFactory*

*This class implements \Zend\ServiceManager\Factory\FactoryInterface, \Zend\ServiceManager\FactoryInterface*

<hr />

### Class: \TwbBundle\Options\Factory\ModuleOptionsFactory

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__invoke(</strong><em>\Interop\Container\ContainerInterface</em> <strong>$container</strong>, <em>mixed</em> <strong>$requestedName</strong>, <em>array</em> <strong>$options=null</strong>)</strong> : <em>void</em> |
| public | <strong>createService(</strong><em>\Zend\ServiceManager\ServiceLocatorInterface</em> <strong>$serviceLocator</strong>)</strong> : <em>mixed</em> |

*This class implements \Zend\ServiceManager\FactoryInterface, \Zend\ServiceManager\Factory\FactoryInterface*

<hr />

### Class: \TwbBundle\View\Helper\Navigation

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getPluginManager()</strong> : <em>[\TwbBundle\View\Helper\Navigation\PluginManager](#class-twbbundleviewhelpernavigationpluginmanager)</em><br /><em>Retrieve plugin loader for navigation helpers Lazy-loads an instance of Navigation\HelperLoader if none currently registered.</em> |

*This class extends \Zend\View\Helper\Navigation*

*This class implements \Zend\View\Helper\HelperInterface, \Zend\EventManager\EventManagerAwareInterface, \Zend\EventManager\EventsCapableInterface, \Zend\View\Helper\Navigation\HelperInterface*

<hr />

### Class: \TwbBundle\View\Helper\Navigation\TwbBundleMenu

| Visibility | Function |
|:-----------|:---------|
| public | <strong>getLiClass()</strong> : <em>string</em> |
| public | <strong>getSubUlClass()</strong> : <em>string</em> |
| public | <strong>getUlId()</strong> : <em>string</em> |
| public | <strong>getUseCaret()</strong> : <em>boolean</em> |
| public | <strong>htmlify(</strong><em>\Zend\Navigation\Page\AbstractPage</em> <strong>$page</strong>, <em>bool</em> <strong>$escapeLabel=true</strong>, <em>bool</em> <strong>$addClassToListItem=false</strong>)</strong> : <em>string</em><br /><em>Returns an HTML string containing an 'a' element for the given page if the page's href is not empty, and a 'span' element if it is empty Overrides {@link AbstractHelper::htmlify()}.</em> |
| public | <strong>setLiClass(</strong><em>string</em> <strong>$liClass</strong>)</strong> : <em>void</em> |
| public | <strong>setSubUlClass(</strong><em>mixed</em> <strong>$subUlClass</strong>)</strong> : <em>[\TwbBundle\View\Helper\Navigation](#class-twbbundleviewhelpernavigation)\$this</em> |
| public | <strong>setUlId(</strong><em>mixed</em> <strong>$ulId</strong>)</strong> : <em>[\TwbBundle\View\Helper\Navigation](#class-twbbundleviewhelpernavigation)\$this</em> |
| public | <strong>setUseCaret(</strong><em>mixed</em> <strong>$useCaret</strong>)</strong> : <em>[\TwbBundle\View\Helper\Navigation](#class-twbbundleviewhelpernavigation)\$this</em> |
| protected | <strong>renderDeepestMenu(</strong><em>\Zend\Navigation\AbstractContainer</em> <strong>$container</strong>, <em>string</em> <strong>$ulClass</strong>, <em>string</em> <strong>$indent</strong>, <em>int/null</em> <strong>$minDepth</strong>, <em>int/null</em> <strong>$maxDepth</strong>, <em>bool</em> <strong>$escapeLabels</strong>, <em>bool</em> <strong>$addClassToListItem</strong>, <em>mixed</em> <strong>$liActiveClass</strong>)</strong> : <em>string</em><br /><em>Renders the deepest active menu within [$minDepth, $maxDepth], (called from {@link renderMenu()})</em> |
| protected | <strong>renderNormalMenu(</strong><em>\Zend\Navigation\AbstractContainer</em> <strong>$container</strong>, <em>string</em> <strong>$ulClass</strong>, <em>string</em> <strong>$indent</strong>, <em>int/null</em> <strong>$minDepth</strong>, <em>int/null</em> <strong>$maxDepth</strong>, <em>bool</em> <strong>$onlyActive</strong>, <em>bool</em> <strong>$escapeLabels</strong>, <em>bool</em> <strong>$addClassToListItem</strong>, <em>mixed</em> <strong>$liActiveClass</strong>)</strong> : <em>string</em><br /><em>Renders a normal menu (called from {@link renderMenu()})</em> |

*This class extends \Zend\View\Helper\Navigation\Menu*

*This class implements \Zend\View\Helper\HelperInterface, \Zend\EventManager\EventManagerAwareInterface, \Zend\EventManager\EventsCapableInterface, \Zend\View\Helper\Navigation\HelperInterface*

<hr />

### Class: \TwbBundle\View\Helper\Navigation\PluginManager

| Visibility | Function |
|:-----------|:---------|
| public | <strong>__construct(</strong><em>mixed</em> <strong>$configOrContainerInstance=null</strong>, <em>array</em> <strong>$v3config=array()</strong>)</strong> : <em>void</em> |

*This class extends \Zend\View\Helper\Navigation\PluginManager*

*This class implements \Zend\ServiceManager\PluginManagerInterface, \Zend\ServiceManager\ServiceLocatorInterface, \Interop\Container\ContainerInterface, \Psr\Container\ContainerInterface*

