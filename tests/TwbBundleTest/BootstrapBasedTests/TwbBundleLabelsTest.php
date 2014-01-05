<?php
namespace TwbBundleTest;
/**
 * Test labels rendering
 * Based on http://getbootstrap.com/components/#labels
 */
class TwbBundleLabelsTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var string
	 */
	protected $expectedPath;

	/**
	 * @var \TwbBundle\Form\View\Helper\TwbBundleLabel
	 */
	protected $labelHelper;

	/**
	 * @see \PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp(){
		$this->expectedPath = __DIR__.DIRECTORY_SEPARATOR.'../../_files/expected-labels'.DIRECTORY_SEPARATOR;
		$oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
		$oRenderer = new \Zend\View\Renderer\PhpRenderer();
		$this->labelHelper = $oViewHelperPluginManager->get('label')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
	}

	public function testAvailableVariations(){
		$sContent = '';

		//Default
		$sContent .= $this->labelHelper->__invoke('Default','label-default').PHP_EOL;

		//Primary
		$sContent .= $this->labelHelper->__invoke('Primary','label-primary').PHP_EOL;

		//Success
		$sContent .= $this->labelHelper->__invoke('Success','label-success').PHP_EOL;

		//Info
		$sContent .= $this->labelHelper->__invoke('Info','label-info').PHP_EOL;

		//Warning
		$sContent .= $this->labelHelper->__invoke('Warning','label-warning').PHP_EOL;

		//Danger
		$sContent .= $this->labelHelper->__invoke('Danger','label-danger').PHP_EOL;

		//Test content
        file_put_contents($this->expectedPath.'available-variations.phtml',$sContent);
        $this->assertStringEqualsFile($this->expectedPath.'available-variations.phtml',$sContent);
	}
}