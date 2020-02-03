<?php
namespace TwbBundleTest\View\Helper;
class TwbBundleAlertTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \TwbBundle\View\Helper\TwbBundleAlert
	 */
	protected $alertHelper;

	/**
	 * @see \PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp(){
		$oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('ViewHelperManager');
		$oRenderer = new \Laminas\View\Renderer\PhpRenderer();
		$this->alertHelper = $oViewHelperPluginManager->get('alert')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRenderWithWrongTypeAttributes(){
		$this->alertHelper->render('test',new \stdClass());
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRenderWithEmptyClassAttributes(){
		$this->alertHelper->render('test',array('class' => ''));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRenderWithWrongTypeClassAttributes(){
		$this->alertHelper->render('test',array('class' => new \stdClass()));
	}
}