<?php
namespace TwbBundleTest\Form\View\Helper;
class TwbBundleFormTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \TwbBundle\Form\View\Helper\TwbBundleForm
	 */
	protected $formHelper;

	/**
	 * @see \PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp(){
		$oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
		$oRenderer = new \Zend\View\Renderer\PhpRenderer();
		$this->formHelper = $oViewHelperPluginManager->get('form')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
	}

	public function testInvoke(){
		return $this->assertSame($this->formHelper,$this->formHelper->__invoke());
	}

	public function testRenderFormWithClassAlreadyDefined(){
		$oForm = new \Zend\Form\Form(null,array('attributes' => array('class' => 'test-class')));
		$this->formHelper->render($oForm->setAttribute('class','test-class'));
		$this->assertEquals('test-class form-horizontal',$oForm->getAttribute('class'));
	}
}