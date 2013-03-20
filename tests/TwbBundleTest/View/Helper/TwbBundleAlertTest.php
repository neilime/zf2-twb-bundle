<?php
namespace TwbBundleTest\View\Helper;
/**
 * Test alert rendering
 * Based on http://twitter.github.com/bootstrap/components.html#alerts
 */
class TwbBundleAlertTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var array
	 */
	private $configuration = array(
		'view_manager' => array(
			'doctype' => 'HTML5'
		)
	);

	/**
	 * @var \TwbBundle\View\Helper\TwbBundleAlert
	 */
	protected $alertHelper;

	public function setUp(){
		$oServiceManager = \TwbBundleTest\Bootstrap::getServiceManager();

		$this->configuration = \Zend\Stdlib\ArrayUtils::merge($oServiceManager->get('Config'),$this->configuration);
		$bAllowOverride = $oServiceManager->getAllowOverride();
		if(!$bAllowOverride)$oServiceManager->setAllowOverride(true);
		$oServiceManager->setService('Config',$this->configuration)->setAllowOverride($bAllowOverride);

		/* @var $oViewHelperPluginManager \Zend\View\HelperPluginManager */
		$oViewHelperPluginManager = $oServiceManager->get('view_helper_manager');

		$oRenderer = new \Zend\View\Renderer\PhpRenderer();
		$this->alertHelper = $oViewHelperPluginManager->get('alert')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
	}

	public function testRenderDefaultAlert(){
		$this->assertEquals(
			$this->alertHelper->render('Test message'),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-alerts/default-alert.html')
		);
	}

	public function testRenderBlockAlert(){
		$this->assertEquals(
			$this->alertHelper->render('Test message','block-alert'),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-alerts/block-alert.html')
		);
	}

	public function testRenderErrorAlert(){
		$this->assertEquals(
			$this->alertHelper->render('Test message','alert-error'),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-alerts/error-alert.html')
		);
	}

	public function testRenderAlertUnclosable(){
		$this->assertEquals(
			$this->alertHelper->render('Test message',null,false),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-alerts/alert-unclosable.html')
		);
	}
}