<?php
namespace TwbBundleTest\View\Helper;
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

		$this->assertEquals(
			$this->formHelper->__invoke($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_VERTICAL),
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

		$this->assertEquals(
			$this->formHelper->__invoke($oForm,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-forms/horizontal.html')
		);
	}
}