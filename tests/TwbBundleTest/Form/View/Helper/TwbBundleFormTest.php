<?php
namespace TwbBundleTest\View\Helper;
/**
 * Test form rendering
 * Based on http://twitter.github.com/bootstrap/base-css.html#forms
 * @author Emilien
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
					'help' => 'Example block-level help text here.'
				)
			)
		))
		->add(array(
			'name' => 'input-checkbox',
			'type' => 'Zend\Form\Element\Checkbox',
			'options' => array(
				'label' => 'Check me out'
			)
		))
		->add(array(
			'name' => 'input-submit',
			'attributes' => array(
				'type' => 'submit',
				'value' => 'Submit'
			)
		)));
		$this->assertEquals(
			$this->formHelper->render($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_VERTICAL),
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
			'name' => 'input-submit',
			'attributes' => array(
				'type' => 'button',
				'value' => 'Search'
			)
		));

		file_put_contents(getcwd().'/TwbBundleTest/_files/expected-forms/search.html',$this->formHelper->render($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_SEARCH));

		$this->assertEquals(
			$this->formHelper->render($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_SEARCH),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-forms/search.html')
		);
	}

	public function testRenderVertical(){
		$oForm = new \Zend\Form\Form();
		$oForm->add(array(
			'name' => 'test-input',
			'attributes' => array(
				'required' => true
			),
			'options' => array(
				'label' => 'test'
			)
		));

		file_put_contents(getcwd().'/TwbBundleTest/_files/expected-forms/vertical.html',$this->formHelper->render($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_VERTICAL));

		$this->assertEquals(
			$this->formHelper->render($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_VERTICAL),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-forms/vertical.html')
		);
	}

	public function testRenderHorizontal(){
		$oForm = new \Zend\Form\Form();
		$oForm->add(array(
			'name' => 'test-input',
			'attributes' => array(
				'required' => true,
			),
			'options' => array(
				'label' => 'test'
			)
		));

		file_put_contents(getcwd().'/TwbBundleTest/_files/expected-forms/horizontal.html',$this->formHelper->render($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL));

		$this->assertEquals(
			$this->formHelper->render($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-forms/horizontal.html')
		);
	}
}