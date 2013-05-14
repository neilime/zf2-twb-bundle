<?php
namespace TwbBundleTest\View\Helper;
/**
 * Test input rendering
 * Based on http://twitter.github.com/bootstrap/base-css.html#forms
 */
class TwbBundleFormRowTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var array
	 */
	private $configuration = array(
		'view_manager' => array(
			'doctype' => 'HTML5'
		)
	);

	/**
	 * @var \TwbBundle\Form\View\Helper\TwbBundleFormRow
	 */
	protected $formRowHelper;

	public function setUp(){
		$oServiceManager = \TwbBundleTest\Bootstrap::getServiceManager();

		$this->configuration = \Zend\Stdlib\ArrayUtils::merge($oServiceManager->get('Config'),$this->configuration);
		$bAllowOverride = $oServiceManager->getAllowOverride();
		if(!$bAllowOverride)$oServiceManager->setAllowOverride(true);
		$oServiceManager->setService('Config',$this->configuration)->setAllowOverride($bAllowOverride);

		/* @var $oViewHelperPluginManager \Zend\View\HelperPluginManager */
		$oViewHelperPluginManager = $oServiceManager->get('view_helper_manager');

		$oRenderer = new \Zend\View\Renderer\PhpRenderer();
		$this->formRowHelper = $oViewHelperPluginManager->get('formRow')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
	}

	public function testRenderPrependedInput(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'placeholder' => 'Username',
			'twb' => array(
				'prepend' => '@'
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/prepended.html',
			$this->formRowHelper->render($oElement)
		);
	}

	public function testRenderAppendedInput(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'placeholder' => 'Username',
			'twb' => array(
				'append' => '.00'
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/appended.html',
			$this->formRowHelper->render($oElement)
		);
	}

	public function testRenderCombinedInput(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'placeholder' => 'Username',
			'twb' => array(
				'prepend' => '$',
				'append' => '.00'
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/combined.html',
			$this->formRowHelper->render($oElement)
		);
	}

	public function testRenderTextAppend(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'placeholder' => 'Username',
			'twb' => array(
				'append' => array('type'=>'text','text'=>'Append text'),
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/text-append.html',
			$this->formRowHelper->render($oElement)
		);
	}

	public function testRenderIconAppend(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'placeholder' => 'Username',
			'twb' => array(
				'append' => array('type'=>'icon','icon'=>'icon-envelope'),
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/icon-append.html',
			$this->formRowHelper->render($oElement)
		);
	}

	public function testRenderOneButtonAppended(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'placeholder' => 'Username',
			'twb' => array(
				'append' => array(
					'type' => 'buttons',
					'buttons' => array('go' => array('options' => array('label' => 'Go!')))
				)
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/one-button-appended.html',
			$this->formRowHelper->render($oElement)
		);
	}

	public function testRenderTwoButtonsAppended(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'placeholder' => 'Username',
			'twb' => array(
				'append' => array(
					'type' => 'buttons',
					'buttons' => array(
						'search' => array('options' => array('label' => 'Search')),
						'options' => array('options' => array('label' => 'Options'))
					)
				)
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/two-buttons-appended.html',
			$this->formRowHelper->render($oElement)
		);
	}

	public function testRenderHelpInline(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'placeholder' => 'Username',
			'twb' => array(
				'help-inline' => 'Inline help text'
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/help-inline.html',
			$this->formRowHelper->render($oElement)
		);
	}

	public function testRenderHelpBlock(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'placeholder' => 'Username',
			'twb' => array(
				'help-block' => 'A longer block of help text that breaks onto a new line and may extend beyond one line.'
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/help-block.html',
			$this->formRowHelper->render($oElement)
		);
	}

	public function testRenderButtonDropdownAppend(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'twb' => array(
				'append' => array(
					'type' => 'buttons',
					'buttons' => array(
						'action' => array('options' => array(
							'label' => 'Action',
							'twb' => array('dropdown' => array('actions' => array('Action','Another action','Something else here','-','Separated link')))
						))
					)
				)
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/button-dropdown-append.html',
			$this->formRowHelper->render($oElement)
		);
	}

	public function testRenderButtonDropdownPrepend(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'twb' => array(
				'prepend' => array(
					'type' => 'buttons',
					'buttons' => array(
						'action' => array('options' => array(
							'label' => 'Action',
							'twb' => array('dropdown' => array('actions' => array('Action','Another action','Something else here','-','Separated link')))
						))
					)
				)
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/button-dropdown-prepend.html',
			$this->formRowHelper->render($oElement)
		);
	}

	public function testRenderButtonDropdownCombined(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'twb' => array(
				'prepend' => array(
					'type' => 'buttons',
					'buttons' => array(
						'action' => array('options' => array(
							'label' => 'Action',
							'twb' => array('dropdown' => array('actions' => array('Action','Another action','Something else here','-','Separated link')))
						))
					)
				),
				'append' => array(
					'type' => 'buttons',
					'buttons' => array(
						'action' => array('options' => array(
							'label' => 'Action',
							'twb' => array('dropdown' => array('actions' => array('Action','Another action','Something else here','-','Separated link')))
						))
					)
				)
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/button-dropdown-combined.html',
			$this->formRowHelper->render($oElement)
		);
	}

	public function testRenderButtonSegmentedDropdownPrepend(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'twb' => array(
				'prepend' => array(
					'type' => 'buttons',
					'buttons' => array(
						'action' => array('options' => array(
							'label' => 'Action',
							'twb' => array('dropdown' => array(
								'segmented' => true,
								'actions' => array('Action','Another action','Something else here','-','Separated link')
							))
						))
					)
				)
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/button-segmented-dropdown-prepend.html',
			$this->formRowHelper->render($oElement)
		);
	}

	public function testRenderButtonSegmentedDropdownAppend(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'twb' => array(
				'append' => array(
					'type' => 'buttons',
					'buttons' => array(
						'action' => array('options' => array(
							'label' => 'Action',
							'twb' => array('dropdown' => array(
								'segmented' => true,
								'actions' => array('Action','Another action','Something else here','-','Separated link')
							))
						))
					)
				)
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/button-segmented-dropdown-append.html',
			$this->formRowHelper->render($oElement)
		);
	}

	public function testRenderValidationStates(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'twb' => array(
				'help-inline' => 'Something may have gone wrong',
				'state' => 'warning'
			),
			'label' => 'Input with warning'
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/validation-states.html',
			$this->formRowHelper->setFormLayout(\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL)->render($oElement)
		);
	}

	public function testRenderRadio(){
		$oElement = new \Zend\Form\Element\Radio('input-radio',array(
			'value_options' => array(
				array('label' => 'test','value' => 1,'label_attributes' => array('class' => 'test'))
			)
		));

		$this->assertStringEqualsFile(
			getcwd().'/TwbBundleTest/_files/expected-inputs/radio.html',
			$this->formRowHelper->render($oElement)
		);
	}

	/**
	 * @expectedException DomainException
	 */
	public function testRenderRadioWithoutValueOptions(){
		$oElement = new \Zend\Form\Element\Radio('input-radio');
		$this->formRowHelper->render($oElement);
	}
}