<?php
namespace TwbBundleTest\View\Helper;
/**
 * Test form rendering
 * Based on http://twitter.github.com/bootstrap/base-css.html#forms
 */
class TwbBundleFormTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var array
	 */
	private $configuration = array(
		'view_manager' => array(
			'doctype' => 'HTML5'
		)
	);

	/**
	 * @var \TwbBundle\Form\View\Helper\TwbBundleForm
	 */
	protected $formHelper;

	public function setUp(){
		$oServiceManager = \TwbBundleTest\Bootstrap::getServiceManager();

		$this->configuration = \Zend\Stdlib\ArrayUtils::merge($oServiceManager->get('Config'),$this->configuration);
		$bAllowOverride = $oServiceManager->getAllowOverride();
		if(!$bAllowOverride)$oServiceManager->setAllowOverride(true);
		$oServiceManager->setService('Config',$this->configuration)->setAllowOverride($bAllowOverride);

		/* @var $oViewHelperPluginManager \Zend\View\HelperPluginManager */
		$oViewHelperPluginManager = $oServiceManager->get('view_helper_manager');

		$oRenderer = new \Zend\View\Renderer\PhpRenderer();
		$this->formHelper = $oViewHelperPluginManager->get('form')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
	}

	public function testInvoke(){
		$this->assertEquals($this->formHelper,$this->formHelper->__invoke());
	}

	public function testRenderDefaultStyles(){
		$oForm = new \Zend\Form\Form();

		$oFieldset = new \Zend\Form\Fieldset('legend');
		$oForm->add($oFieldset->setLabel('Legend')->add(array(
			'name' => 'input-text',
			'attributes' => array(
				'placeholder' => 'Type something...'
			),
			'options' => array(
				'label' => 'Label name',
				'twb' => array(
					'help-block' => 'Example block-level help text here.'
				)
			)
		))
		->add(array(
			'name' => 'input-checkbox',
			'type' => 'checkbox',
			'options' => array(
				'label' => 'Check me out',
				'use_hidden_element' => false
			)
		))
		->add(array(
			'name' => 'button-submit',
			'type' => 'button',
			'attributes' => array(
				'type' => 'submit'
			),
			'options' => array(
				'label' => 'Submit'
			)
		)));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-forms/default.html',
			$this->formHelper->__invoke($oForm,null)
		);
	}

	public function testRenderSearchForm(){
		$oForm = new \Zend\Form\Form();
		$oForm
		->setAttribute('class', 'test-class')
		->add(array(
			'name' => 'input-text',
			'attributes' => array(
				'class' => 'search-query input-medium'
			)
		))
		->add(array(
			'name' => 'button-submit',
			'type' => 'button',
			'attributes' => array(
				'type' => 'submit'
			),
			'options' => array(
				'label' => 'Search'
			)
		));
		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-forms/search.html',
			$this->formHelper->__invoke($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_SEARCH)
		);
	}

	public function testRenderInlineForm(){
		$oForm = new \Zend\Form\Form();
		$oForm->add(array(
			'name' => 'input-text-email',
			'attributes' => array(
				'class' => 'input-small',
				'placeholder' => 'Email'
			)
		))
		->add(array(
			'name' => 'input-text-password',
			'attributes' => array(
				'class' => 'input-small',
				'placeholder' => 'Password'
			)
		))
		->add(array(
			'name' => 'input-checkbox',
			'type' => 'checkbox',
			'options' => array(
				'label' => 'Remember me',
				'use_hidden_element' => false
			)
		))
		->add(array(
			'name' => 'button-submit',
			'type' => 'button',
			'attributes' => array(
				'type' => 'submit'
			),
			'options' => array(
				'label' => 'Sign in'
			)
		));
		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-forms/inline.html',
			$this->formHelper->__invoke($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE)
		);
	}

	public function testRenderHorizontalForm(){
		$oForm = new \Zend\Form\Form();
		$oForm->add(array(
			'name' => 'input-text-email',
			'attributes' => array(
				'class' => 'input-small',
				'placeholder' => 'Email'
			),
			'options' => array(
				'label' => 'Email'
			)
		))
		->add(array(
			'name' => 'input-text-password',
			'attributes' => array(
				'class' => 'input-small',
				'placeholder' => 'Password'
			),
			'options' => array(
				'label' => 'Password'
			)
		))
		->add(array(
			'name' => 'input-checkbox',
			'type' => 'checkbox',
			'options' => array(
				'label' => 'Remember me',
				'use_hidden_element' => false
			)
		))
		->add(array(
			'name' => 'button-submit',
			'type' => 'button',
			'attributes' => array(
				'type' => 'submit'
			),
			'options' => array(
				'label' => 'Sign in'
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-forms/horizontal.html',
			$this->formHelper->__invoke($oForm)
		);
	}

	public function testRenderSearchFormButtonAppend(){
		$oForm = new \Zend\Form\Form();
		$oForm->add(array(
			'name' => 'input-search-append',
			'attributes' => array(
				'class' => 'search-query input-medium'
			),
			'options' => array('twb' => array(
				'append' => array(
					'type' => 'buttons',
					'buttons' => array(
						'search-submit-append' => array('options' => array('label' => 'Search'),'attributes' => array('type' => 'submit'))
					)
				)
			))
		))->add(array(
			'name' => 'input-search-prepend',
			'attributes' => array(
				'class' => 'search-query input-medium'
			),
			'options' => array('twb' => array(
				'prepend' => array(
					'type' => 'buttons',
					'buttons' => array(
						'search-submit-prepend' => array('options' => array('label' => 'Search'),'attributes' => array('type' => 'submit'))
					)
				)
			))
		));
		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-forms/search-button-append.html',
			$this->formHelper->__invoke($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_SEARCH)
		);
	}

	public function testRenderFormActions(){
		$oForm = new \Zend\Form\Form();
		$oForm->add(array(
			'name' => 'button-submit',
			'type' => 'button',
			'attributes' => array(
				'type' => 'submit',
				'class' => 'btn-primary'
			),
			'options' => array(
				'label' => 'Save changes',
				'twb' => array('formAction' => true)
			)
		))->add(array(
			'name' => 'button-cancel',
			'type' => 'button',
			'options' => array(
				'label' => 'Cancel',
				'twb' => array('formAction' => true)
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-forms/form-actions.html',
			$this->formHelper->__invoke($oForm,null)
		);
	}

	public function testRenderFormValidation(){
		$oInputFilter = new \Zend\InputFilter\InputFilter();

		$oForm = new \Zend\Form\Form();
		$oForm->add(array(
			'name' => 'input-text-email',
			'attributes' => array(
				'class' => 'input-small',
				'placeholder' => 'Email'
			),
			'options' => array(
				'label' => 'Email'
			)
		))->add(array(
			'name' => 'button-submit',
			'type' => 'button',
			'attributes' => array('type' => 'submit'),
			'options' => array('label' => 'Submit')
		))->setInputFilter($oInputFilter->add(array(
			'name' => 'input-text-email',
			'required' => true,
			'filters' => array(array('name' => 'StringTrim')),
			'validators' => array(
				array('name'=> 'EmailAddress'),
			)
		)));

		$this->assertFalse($oForm->setData(array('input-text-email' => 'test'))->isValid());

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-forms/form-validation.html',
			$this->formHelper->__invoke($oForm)
		);
	}

	public function testRenderCheckboxesAndRadios(){
		$oForm = new \Zend\Form\Form();
		$oForm->add(new \Zend\Form\Element\Checkbox('input-checkbox',array(
			'use_hidden_element' => false,
			'label' => 'Option one is this and that-be sure to include why it\'s great'
		)))
		->add(new \Zend\Form\Element\Radio('optionsRadios',array(
			'value_options' => array('Option one is this and that-be sure to include why it\'s great')
		)))
		->add(new \Zend\Form\Element\Radio('optionsRadios',array(
			'value_options' => array('Option two can be something else and selecting it will deselect option one')
		)));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-forms/checkboxes-and-radios-stacked.html',
			$this->formHelper->__invoke($oForm,null)
		);
	}

	public function testRenderInlineCheckboxesAndRadios(){
		$oForm = new \Zend\Form\Form();
		$oForm->add(new \Zend\Form\Element\Checkbox('input-checkbox',array(
			'use_hidden_element' => false,
			'label' => '1'
		)))->add(new \Zend\Form\Element\Checkbox('input-checkbox',array(
			'use_hidden_element' => false,
			'label' => '2'
		)))
		->add(new \Zend\Form\Element\Checkbox('input-checkbox',array(
			'use_hidden_element' => false,
			'label' => '3'
		)))
		->add(new \Zend\Form\Element\Radio('optionsRadios',array(
			'value_options' => array(1,2,3)
		)));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-forms/checkboxes-and-radios-inline.html',
			$this->formHelper->__invoke($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE)
		);
	}
}