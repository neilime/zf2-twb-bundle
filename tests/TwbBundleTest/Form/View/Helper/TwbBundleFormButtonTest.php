<?php
namespace TwbBundleTest\Form\View\Helper;
class TwbBundleFormButtonTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \TwbBundle\Form\View\Helper\TwbBundleFormButton
	 */
	protected $formButtonHelper;

	/**
	 * @see \PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp(){
		$oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
		$oRenderer = new \Zend\View\Renderer\PhpRenderer();
		$this->formButtonHelper = $oViewHelperPluginManager->get('formButton')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
	}

	/**
	 * @expectedException \DomainException
	 */
	public function testRenderElementWithEmptyButtonContentandLabel(){
		$this->formButtonHelper->render(new \Zend\Form\Element(null,array('dropdown' => array('test'))));
	}
}