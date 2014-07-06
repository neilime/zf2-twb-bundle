<?php
/**
 * Project: zf2-twb-bundle, File: TwbBundleFontAwesomeTest.php
 * @author Michael Schakulat <michael@fetchit.de>
 * @package zf2-twb-bundle
 */

namespace TwbBundleTest\View\Helper;

use TwbBundle\View\Helper\TwbBundleFontAwesome;
use TwbBundleTest\Bootstrap;
use Zend\View\Renderer\PhpRenderer;

class TwbBundleFontAwesomeTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var TwbBundleFontAwesome
     */
    protected $fontAwesomeHelper;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp() {
        $oViewHelperPluginManager = Bootstrap::getServiceManager()
            ->get('view_helper_manager');
        $oRenderer = new PhpRenderer();
        $this->fontAwesomeHelper = $oViewHelperPluginManager->get('fontAwesome')
            ->setView(
                $oRenderer->setHelperPluginManager($oViewHelperPluginManager)
            );
    }

    public function testInvoke() {
        $this->assertSame(
            $this->fontAwesomeHelper, $this->fontAwesomeHelper->__invoke()
        );
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRenderWithWrongTypeFontAwesome() {
        $this->fontAwesomeHelper->render(new \stdClass());
    }

    public function testRenderWithEmptyClassAttributes() {
        $this->assertEquals(
            '<span class="fa&#x20;fa-test"></span>',
            $this->fontAwesomeHelper->render('test', array('class' => ''))
        );
    }

    public function testRenderWithDefinedClassAttributes() {
        $this->assertEquals(
            '<span class="test&#x20;fa&#x20;fa-test"></span>',
            $this->fontAwesomeHelper->render('test', array('class' => 'test'))
        );
    }
} 