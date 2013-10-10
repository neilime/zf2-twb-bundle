<?php
namespace TwbBundleTest\Form\View\Helper;
class TwbBundleFormRadioTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \TwbBundle\Form\View\Helper\TwbBundleFormRadio
	 */
	protected $formRadioHelper;

	/**
	 * @see \PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp(){
		$oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
		$oRenderer = new \Zend\View\Renderer\PhpRenderer();
		$this->formRadioHelper = $oViewHelperPluginManager->get('formRadio')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
	}

	public function testRenderOptionsWithPrependingLabel(){
		$oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormRadio');
		$oReflectionMethod = $oReflectionClass->getMethod('renderOptions');
		$oReflectionMethod->setAccessible(true);

		$this->formRadioHelper->setLabelPosition(\TwbBundle\Form\View\Helper\TwbBundleFormRadio::LABEL_PREPEND);
		$this->assertEquals(
			'<label>test<input value="0" checked="checked"></label>',
			$oReflectionMethod->invoke($this->formRadioHelper,new \Zend\Form\Element\MultiCheckbox(),array('test'),array('test'),array())
		);
	}
}