<?php
namespace TwbBundleTest\Form\View\Helper;
class TwbBundleFormRowTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \TwbBundle\Form\View\Helper\TwbBundleFormRow
	 */
	protected $formRowHelper;

	/**
	 * @see \PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp(){
		$oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
		$oRenderer = new \Zend\View\Renderer\PhpRenderer();
		$oRenderer->setResolver(\TwbBundleTest\Bootstrap::getServiceManager()->get('ViewResolver'));
		$this->formRowHelper = $oViewHelperPluginManager->get('formRow')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
		$this->formRowHelper->setPartial(null);
	}

	public function testRenderPartial(){
		$this->formRowHelper->setPartial('partial-row');
		$this->assertEquals('Partial Row : <input name="test-element" class="form-control" type="text" value="">',$this->formRowHelper->render(new \Zend\Form\Element('test-element')));
	}

	public function testRenderAddOnWithValidationStateAndDefinedLabelClass(){
		$oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormRow');
		$oReflectionMethod = $oReflectionClass->getMethod('renderElement');
		$oReflectionMethod->setAccessible(true);

		$oElement = new \Zend\Form\Element('test-element',array('validation-state' => 'warning'));
		$oElement
		->setLabel('test-label')
		->setLabelAttributes(array('class' => 'test-label-class'));

		$this->assertEquals(
			'<label class="test-label-class control-label" for="test-element">test-label</label><input name="test-element" class="form-control" type="text" value="">',
			$oReflectionMethod->invoke($this->formRowHelper,$oElement)
		);
	}

	public function testRenderAddOnWithInlineLayoutAndDefinedLabelClass(){
		$oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormRow');
		$oReflectionMethod = $oReflectionClass->getMethod('renderElement');
		$oReflectionMethod->setAccessible(true);

		$oElement = new \Zend\Form\Element('test-element',array('twb-layout' => \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE));
		$oElement
		->setLabel('test-label')
		->setLabelAttributes(array('class' => 'test-label-class'));

		$this->assertEquals(
			'<label class="test-label-class sr-only" for="test-element">test-label</label><input name="test-element" class="form-control" type="text" value="">',
			$oReflectionMethod->invoke($this->formRowHelper,$oElement)
		);
	}

	public function testRenderAddOnWithHorizontalLayoutAndDefinedLabelClass(){
		$oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormRow');
		$oReflectionMethod = $oReflectionClass->getMethod('renderElement');
		$oReflectionMethod->setAccessible(true);

		$oElement = new \Zend\Form\Element('test-element',array('twb-layout' => \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL));
		$oElement
		->setLabel('test-label')
		->setLabelAttributes(array('class' => 'test-label-class'));

		$this->assertEquals(
			'<label class="test-label-class col-lg-2 control-label" for="test-element">test-label</label><div class="col-lg-10"><input name="test-element" class="form-control" type="text" value=""></div>',
			$oReflectionMethod->invoke($this->formRowHelper,$oElement)
		);
	}

	/**
	 * @expectedException \DomainException
	 */
	public function testRenderAddOnWithWrongLayout(){
		$oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormRow');
		$oReflectionMethod = $oReflectionClass->getMethod('renderElement');
		$oReflectionMethod->setAccessible(true);
		$oReflectionMethod->invoke($this->formRowHelper,new \Zend\Form\Element('test-element',array('label' => 'test-label','twb-layout' => 'wrong')));
	}

	public function testRenderErrorsWithInputErrorClass(){
		$this->formRowHelper->setInputErrorClass('input-error');
		$oElement = new \Zend\Form\Element\Text('input-text');
		$oElement->setAttribute('class', 'test-class');
		$oElement->setMessages(array(
			'This is an error message',
			'This is an another one error message'
		));

		//Test content
		$this->assertEquals(
			'<div class="form-group  has-error"><input type="text" name="input-text" class="test-class input-error form-control" value=""><ul class="help-block"><li>This is an error message</li><li>This is an another one error message</li></ul></div>'.PHP_EOL,
			$this->formRowHelper->__invoke($oElement)
		);
	}

	public function testRenderHiddenElement(){
		$this->formRowHelper->setInputErrorClass('input-error');
		$oElement = new \Zend\Form\Element\Hidden('input-hidden');
		//Test content
		$this->assertEquals(
			'<input type="hidden" name="input-hidden" class="form-control" value="">',
			$this->formRowHelper->__invoke($oElement)
		);
	}
}