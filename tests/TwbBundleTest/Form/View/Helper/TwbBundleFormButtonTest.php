<?php
namespace TwbBundleTest\View\Helper;
/**
 * Test buttons rendering
 * Based on http://twitter.github.com/bootstrap/base-css.html#buttons
 */
class TwbBundleFormButtonTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var array
	 */
	private $configuration = array(
		'view_manager' => array(
			'doctype' => 'HTML5'
		)
	);

	/**
	 * @var \TwbBundle\Form\View\Helper\TwbBundleFormButton
	 */
	protected $formButtonHelper;

	public function setUp(){
		$oServiceManager = \TwbBundleTest\Bootstrap::getServiceManager();

		$this->configuration = \Zend\Stdlib\ArrayUtils::merge($oServiceManager->get('Config'),$this->configuration);
		$bAllowOverride = $oServiceManager->getAllowOverride();
		if(!$bAllowOverride)$oServiceManager->setAllowOverride(true);
		$oServiceManager->setService('Config',$this->configuration)->setAllowOverride($bAllowOverride);

		/* @var $oViewHelperPluginManager \Zend\View\HelperPluginManager */
		$oViewHelperPluginManager = $oServiceManager->get('view_helper_manager');

		$oRenderer = new \Zend\View\Renderer\PhpRenderer();
		$this->formButtonHelper = $oViewHelperPluginManager->get('formButton')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
	}

	public function testRenderDefaultButtonWithoutLabel(){
		$oButton = new \Zend\Form\Element\Button('button-default',array(
			'label' => 'Default'
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-buttons/default.html',
			$this->formButtonHelper->render($oButton)
		);

	}

	/**
	 * @expectedException LogicException
	 */
	public function testRenderDefaultButton(){
		$oButton = new \Zend\Form\Element\Button('button-default');
		$this->formButtonHelper->render($oButton);
	}

	public function testRenderPrimaryButton(){
		$oButton = new \Zend\Form\Element\Button('button-primary',array(
			'label' => 'Primary'
		));
		$oButton->setAttribute('class','btn-primary');

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-buttons/primary.html',
			$this->formButtonHelper->render($oButton)
		);
	}

	public function testRenderButtonIcon(){
		$oButton = new \Zend\Form\Element\Button('button-icon',array(
			'label' => '',
			'twb' => array('icon' => 'icon-star')
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-buttons/icon.html',
			$this->formButtonHelper->render($oButton)
		);
	}

	public function testRenderButtonIconLabel(){
		$oButton = new \Zend\Form\Element\Button('button-icon-label',array(
			'label' => 'Star',
			'twb' => array('icon' => 'icon-star')
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-buttons/icon-label.html',
			$this->formButtonHelper->render($oButton)
		);
	}

	public function testRenderButtonIconLabelSegmentedDropdown(){
		$oButton = new \Zend\Form\Element\Button('button-icon-label-segmented-dropdown',array(
			'label' => 'User',
			'twb' => array(
				'icon' => 'icon-user icon-white',
				'dropdown' => array(
					'segmented' => true,
					'actions' => array(
						'edit' => array('label' => 'Edit', 'icon' => 'icon-pencil'),
						'delete' => array('label' => 'Belete', 'icon' => 'icon-trash'),
						'Ban' => array('content' => 'Ban', 'icon' => 'icon-ban-circle'),
						'-',
						'Make admin'
					)
				)
			)
		));
		$oButton->setAttribute('class','btn-primary');

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-buttons/button-icon-label-segmented-dropdown.html',
			$this->formButtonHelper->render($oButton)
		);
	}

	/**
	 * @expectedException InvalidArgumentException
	 */
	public function testRenderButtonDropdownWithWrongConfig(){
		$oButton = new \Zend\Form\Element\Button('button-dropdown-with-wrong-config',array(
			'label' => 'User',
			'twb' => array(
				'dropdown' => array(
					'actions' => array(
						'wrong' => new \stdClass()
					)
				)
			)
		));
		$this->formButtonHelper->render($oButton);
	}

	public function testRenderSegmentedDropup(){
		$oButton = new \Zend\Form\Element\Button('segmented-dropup',array(
			'label' => 'Dropup',
			'twb' => array(
				'dropup' => array(
					'segmented' => true,
					'actions' => array('Action','Another action','Something else here','-','Separated link')
				)
			)
		));
		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-buttons/segmented-dropup.html',
			$this->formButtonHelper->render($oButton)
		);
	}

	public function testRenderSegmentedRightDropup(){
		$oButton = new \Zend\Form\Element\Button('segmented-right-dropup',array(
			'label' => 'Right dropup',
			'twb' => array(
				'dropup' => array(
					'segmented' => true,
					'pull' => 'right',
					'actions' => array('Action','Another action','Something else here','-','Separated link')
				)
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-buttons/segmented-right-dropup.html',
			$this->formButtonHelper->render($oButton)
		);
	}
}