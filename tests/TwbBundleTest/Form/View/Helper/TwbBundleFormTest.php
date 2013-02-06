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

		$this->assertEquals(
			$this->formHelper->render($oForm),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-forms/default.html')
		);
	}

	public function testRenderSearchForm(){
		$oForm = new \Zend\Form\Form();
		$oForm->add(array(
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

		$this->assertEquals(
			$this->formHelper->render($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_SEARCH),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-forms/search.html')
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

		$this->assertEquals(
			$this->formHelper->render($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-forms/inline.html')
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

		$this->assertEquals(
			$this->formHelper->render($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-forms/horizontal.html')
		);
	}
}