<?php
namespace TwbBundleTest\Form\View\Helper;
class TwbBundleFormCheckboxTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \TwbBundle\Form\View\Helper\TwbBundleFormCheckbox
	 */
	protected $formCheckboxHelper;

	/**
	 * @see \PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp(){
		$oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
		$oRenderer = new \Zend\View\Renderer\PhpRenderer();
		$this->formCheckboxHelper = $oViewHelperPluginManager->get('formCheckbox')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
	}

	/**
	 * @expectedException \InvalidArgumentException
	 */
	public function testRenderElementWithWrongElement(){
		$this->formCheckboxHelper->render(new \Zend\Form\Element());
	}

	/**
	 * @expectedException \LogicException
	 */
	public function testRenderElementWithEmptyName(){
		$this->formCheckboxHelper->render(new \Zend\Form\Element\Checkbox(''));
	}
}