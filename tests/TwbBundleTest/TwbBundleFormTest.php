<?php
namespace TwbBundleTest;
/**
 * Test form rendering
 * Based on http://getbootstrap.com/css/#forms
 */
class TwbBundleFormTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var string
	 */
	protected $expectedPath;

	/**
	 * @var \TwbBundle\Form\View\Helper\TwbBundleForm
	 */
	protected $formHelper;

	/**
	 * @see \PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp(){
		$this->expectedPath = __DIR__.DIRECTORY_SEPARATOR.'_files/expected-forms'.DIRECTORY_SEPARATOR;
		$oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
		$oRenderer = new \Zend\View\Renderer\PhpRenderer();
		$this->formHelper = $oViewHelperPluginManager->get('form')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
	}

	/**
	 * Test http://getbootstrap.com/css/#forms-example
	 */
	public function testBasicExample(){
		$oForm = new \Zend\Form\Form();
		$oForm->add(array(
			'name' => 'input-email',
			'attributes' => array(
				'type' => 'email',
				'placeholder' => 'Enter email',
				'id' => 'exampleInputEmail1'
			),
			'options' => array('label' => 'Email address')
		))->add(array(
			'name' => 'input-password',
			'attributes' => array(
				'type' => 'password',
				'placeholder' => 'Password',
				'id' => 'exampleInputPassword1'
			),
			'options' => array('label' => 'Password')
		))->add(array(
			'name' => 'input-file',
			'attributes' => array(
				'type' => 'file',
				'id' => 'exampleInputFile'
			),
			'options' => array(
				'label' => 'File input',
				'twb' => array('help-block' => 'Example block-level help text here.')
			)
		))->add(array(
			'name' => 'input-checkbox',
			'type' => 'checkbox',
			'options' => array('label' => 'Check me out')
		))->add(array(
			'name' => 'button-submit',
			'type' => 'button',
			'attributes' => array('type' => 'submit'),
			'options' => array('label' => 'Submit')
		));

		file_put_contents($this->expectedPath.'basic-example.phtml',
    		str_replace(PHP_EOL,"\n",$this->formHelper->__invoke($oForm,null)));

		$this->assertStringEqualsFile(
			$this->expectedPath.'basic-example.phtml',
    		str_replace(PHP_EOL,"\n",$this->formHelper->__invoke($oForm,null))
    	);
	}

	/**
	 * Test http://getbootstrap.com/css/#forms-inline
	 */
	public function testInlineForm(){
		$oForm = new \Zend\Form\Form();
		$oForm->add(array(
			'name' => 'input-email',
			'attributes' => array(
				'type' => 'email',
				'placeholder' => 'Enter email',
				'id' => 'exampleInputEmail2'
			),
			'options' => array('label' => 'Email address')
		))->add(array(
			'name' => 'input-password',
			'attributes' => array(
				'type' => 'password',
				'placeholder' => 'Password',
				'id' => 'exampleInputPassword2'
			),
			'options' => array('label' => 'Password')
		))->add(array(
			'name' => 'input-checkbox',
			'type' => 'checkbox',
			'options' => array('label' => 'Remember me')
		))->add(array(
			'name' => 'button-submit',
			'type' => 'button',
			'attributes' => array('type' => 'submit'),
			'options' => array('label' => 'Sign in')
		));

		file_put_contents($this->expectedPath.'inline-form.phtml',
			str_replace(PHP_EOL,"\n",$this->formHelper->__invoke($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE)));

		$this->assertStringEqualsFile(
			$this->expectedPath.'inline-form.phtml',
			str_replace(PHP_EOL,"\n",$this->formHelper->__invoke($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE))
		);
	}

	/**
	 * Test http://getbootstrap.com/css/#forms-horizontal
	 */
	public function testHorizontalform(){
		$oForm = new \Zend\Form\Form();
		$oForm->add(array(
			'name' => 'input-email',
			'attributes' => array(
				'type' => 'email',
				'placeholder' => 'Enter email',
				'id' => 'inputEmail1'
			),
			'options' => array('label' => 'Email')
		))->add(array(
			'name' => 'input-password',
			'attributes' => array(
				'type' => 'password',
				'placeholder' => 'Password',
				'id' => 'inputPassword1'
			),
			'options' => array('label' => 'Password')
		))->add(array(
			'name' => 'input-checkbox',
			'type' => 'checkbox',
			'options' => array('label' => 'Remember me')
		))->add(array(
			'name' => 'button-submit',
			'type' => 'button',
			'attributes' => array('type' => 'submit'),
			'options' => array('label' => 'Sign in')
		));

		$this->assertStringEqualsFile(
			$this->expectedPath.'horizontal-form.phtml',
			str_replace(PHP_EOL,"\n",$this->formHelper->__invoke($oForm))
		);
	}


	/**
	 * Test http://getbootstrap.com/css/#forms-controls
	 */
	public function testSupportedControlsform(){
		$oForm = new \Zend\Form\Form();
		$oForm->add(array(
			'name' => 'input-text',
			'attributes' => array(
				'type' => 'text',
				'placeholder' => 'Text input',
			)
		))->add(array(
			'name' => 'input-text-area',
			'type' => 'textarea',
			'attributes' => array(
				'row' => 3
			)
		))->add(array(
			'name' => 'input-checkbox',
			'type' => 'checkbox',
			'options' => array('label' => 'Option one is this and that—be sure to include why it\'s great')
		))->add(array(
			'name' => 'optionsRadios',
			'type' => 'radio',
			'options' => array(
				'label' => 'Option one is this and that—be sure to include why it\'s great',
				'value_options' => array(
					'option1' => 'Option one is this and that—be sure to include why it\'s great',
					'optionsRadios2' => ' Option two can be something else and selecting it will deselect option one'
				)
			),
		))->add(array(
			'name' => 'select',
			'type' => 'select',
			'options' => array('value_options' => array(1,2,3,4,5))
		))->add(array(
			'name' => 'multiple-select',
			'type' => 'select',
			'options' => array('value_options' => array(1,2,3,4,5)),
			'attributes' => array('multiple' => true)
		));

		file_put_contents($this->expectedPath.'supported-controls-form.phtml',
			str_replace(PHP_EOL,"\n",$this->formHelper->__invoke($oForm,null)));

		$this->assertStringEqualsFile(
			$this->expectedPath.'supported-controls-form.phtml',
			str_replace(PHP_EOL,"\n",$this->formHelper->__invoke($oForm,null))
		);
	}
}