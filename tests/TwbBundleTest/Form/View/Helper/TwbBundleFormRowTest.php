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

		$this->assertEquals(
			$this->formRowHelper->render($oElement),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-inputs/prepended.html')
		);
	}

	public function testRenderAppendedInput(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'placeholder' => 'Username',
			'twb' => array(
				'append' => '.00'
			)
		));

		$this->assertEquals(
			$this->formRowHelper->render($oElement),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-inputs/appended.html')
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

		$this->assertEquals(
			$this->formRowHelper->render($oElement),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-inputs/combined.html')
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

		$this->assertEquals(
			$this->formRowHelper->render($oElement),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-inputs/one-button-appended.html')
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

		$this->assertEquals(
			$this->formRowHelper->render($oElement),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-inputs/two-buttons-appended.html')
		);
	}

	public function testRenderHelpInline(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'placeholder' => 'Username',
			'twb' => array(
				'help-inline' => 'Inline help text'
			)
		));

		$this->assertEquals(
			$this->formRowHelper->render($oElement),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-inputs/help-inline.html')
		);
	}

	public function testRenderHelpBlock(){
		$oElement = new \Zend\Form\Element('input-text',array(
			'placeholder' => 'Username',
			'twb' => array(
				'help-block' => 'A longer block of help text that breaks onto a new line and may extend beyond one line.'
			)
		));

		$this->assertEquals(
			$this->formRowHelper->render($oElement),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-inputs/help-block.html')
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
							'dropdown' => array('actions' => array('Action','Another action','Something else here','-','Separated link'))
						))
					)
				)
			)
		));

		$this->assertEquals(
			$this->formRowHelper->render($oElement),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-inputs/button-dropdown-append.html')
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
							'dropdown' => array('actions' => array('Action','Another action','Something else here','-','Separated link'))
						))
					)
				)
			)
		));

		$this->assertEquals(
			$this->formRowHelper->render($oElement),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-inputs/button-dropdown-prepend.html')
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
							'dropdown' => array('actions' => array('Action','Another action','Something else here','-','Separated link'))
						))
					)
				),
				'append' => array(
					'type' => 'buttons',
					'buttons' => array(
						'action' => array('options' => array(
							'label' => 'Action',
							'dropdown' => array('actions' => array('Action','Another action','Something else here','-','Separated link'))
						))
					)
				)
			)
		));

		$this->assertEquals(
			$this->formRowHelper->render($oElement),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-inputs/button-dropdown-combined.html')
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
							'dropdown' => array(
								'segmented' => true,
								'actions' => array('Action','Another action','Something else here','-','Separated link')
							)
						))
					)
				)
			)
		));

		$this->assertEquals(
			$this->formRowHelper->render($oElement),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-inputs/button-segmented-dropdown-prepend.html')
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
							'dropdown' => array(
								'segmented' => true,
								'actions' => array('Action','Another action','Something else here','-','Separated link')
							)
						))
					)
				)
			)
		));

		$this->assertEquals(
			$this->formRowHelper->render($oElement),
			file_get_contents(getcwd().'/TwbBundleTest/_files/expected-inputs/button-segmented-dropdown-append.html')
		);
	}
}