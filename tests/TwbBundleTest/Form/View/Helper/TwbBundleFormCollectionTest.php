<?php
namespace TwbBundleTest\Form\View\Helper;
class TwbBundleFormCollectionTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \TwbBundle\Form\View\Helper\TwbBundleFormCollection
	 */
	protected $formCollectionHelper;

	/**
	 * @see \PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp(){
		$oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
		$oRenderer = new \Zend\View\Renderer\PhpRenderer();
		$this->formCollectionHelper = $oViewHelperPluginManager->get('formCollection')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
	}

	public function testRenderWithSohouldWrap(){
		$this->formCollectionHelper->setShouldWrap(true);
		$this->assertEquals(
			'<fieldset ><legend >test-element</legend></fieldset>',
			$this->formCollectionHelper->render(new \zend\Form\Element(null,array('label' => 'test-element')))
		);
	}
}