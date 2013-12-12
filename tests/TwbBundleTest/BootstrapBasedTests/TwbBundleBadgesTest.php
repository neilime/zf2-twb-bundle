<?php
namespace TwbBundleTest;
/**
 * Test badges rendering
 * Based on http://getbootstrap.com/components/#badges
 */
class TwbBundleBadgesTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var string
	 */
	protected $expectedPath;

	/**
	 * @var \TwbBundle\Form\View\Helper\TwbBundleBadge
	 */
	protected $badgeHelper;

	/**
	 * @see \PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp(){
		$this->expectedPath = __DIR__.DIRECTORY_SEPARATOR.'../../_files/expected-badges'.DIRECTORY_SEPARATOR;
		$oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
		$oRenderer = new \Zend\View\Renderer\PhpRenderer();
		$this->badgeHelper = $oViewHelperPluginManager->get('badge')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
	}

	public function testBadges(){
		$sContent = '';

		//Default
		$sContent .= $this->badgeHelper->__invoke('42').PHP_EOL;

		//Pull-right
		$sContent .= $this->badgeHelper->__invoke('3',array('class' => 'pull-right')).PHP_EOL;

		$this->assertStringEqualsFile($this->expectedPath.'badges.phtml',$sContent);
	}
}