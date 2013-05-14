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
		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-alerts/default-alert.html',
			$this->alertHelper->__invoke('Test message')
		);
	}

	public function testRenderBlockAlert(){
		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-alerts/block-alert.html',
			$this->alertHelper->__invoke('Test message','block-alert')
		);
	}

	public function testRenderErrorAlert(){
		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-alerts/error-alert.html',
			$this->alertHelper->__invoke('Test message','alert-error')
		);
	}

	public function testRenderAlertUnclosable(){
		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-alerts/alert-unclosable.html',
			$this->alertHelper->__invoke('Test message',null,false)
		);
	}
}