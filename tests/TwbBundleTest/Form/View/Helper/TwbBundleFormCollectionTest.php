<?php
namespace TwbBundleTest\View\Helper;
/**
 * Test buttons rendering
 * Based on http://twitter.github.com/bootstrap/base-css.html#buttons
 */
class TwbBundleFormCollectionTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var array
	 */
	private $configuration = array(
		'view_manager' => array(
			'doctype' => 'HTML5'
		)
	);

	/**
	 * @var \TwbBundle\Form\View\Helper\TwbBundleFormCollection
	 */
	protected $formCollectionHelper;

	public function setUp(){
		$oServiceManager = \TwbBundleTest\Bootstrap::getServiceManager();

		$this->configuration = \Zend\Stdlib\ArrayUtils::merge($oServiceManager->get('Config'),$this->configuration);
		$bAllowOverride = $oServiceManager->getAllowOverride();
		if(!$bAllowOverride)$oServiceManager->setAllowOverride(true);
		$oServiceManager->setService('Config',$this->configuration)->setAllowOverride($bAllowOverride);

		/* @var $oViewHelperPluginManager \Zend\View\HelperPluginManager */
		$oViewHelperPluginManager = $oServiceManager->get('view_helper_manager');

		$oRenderer = new \Zend\View\Renderer\PhpRenderer();
		$this->formCollectionHelper = $oViewHelperPluginManager->get('formCollection')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
	}

    public function testRender(){
    	$oRootFieldSet = new \Zend\Form\Fieldset('root');
    	$oFieldset = new \Zend\Form\Fieldset('legend');
    	$oRootFieldSet->add($oFieldset->setLabel('Legend')->add(array(
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
    	)));

    	$this->assertEquals(
    		$this->formCollectionHelper->render($oRootFieldSet),
    		file_get_contents(getcwd().'/TwbBundleTest/_files/expected-collections/default.html')
    	);
    }
}