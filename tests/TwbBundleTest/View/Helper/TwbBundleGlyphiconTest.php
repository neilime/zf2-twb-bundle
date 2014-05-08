<?php

namespace TwbBundleTest\View\Helper;

class TwbBundleGlyphiconTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \TwbBundle\View\Helper\TwbBundleGlyphicon
     */
    protected $glyphiconHelper;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp() {
        $oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
        $oRenderer = new \Zend\View\Renderer\PhpRenderer();
        $this->glyphiconHelper = $oViewHelperPluginManager->get('glyphicon')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
    }

    public function testInvoke() {
        $this->assertSame($this->glyphiconHelper, $this->glyphiconHelper->__invoke());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRenderWithWrongTypeGlyphicon() {
        $this->glyphiconHelper->render(new \stdClass());
    }

    public function testRenderWithEmptyClassAttributes() {
        $this->assertEquals('<span class="glyphicon&#x20;glyphicon-test"></span>', $this->glyphiconHelper->render('test', array('class' => '')));
    }

    public function testRenderWithDefinedClassAttributes() {
        $this->assertEquals('<span class="test&#x20;glyphicon&#x20;glyphicon-test"></span>', $this->glyphiconHelper->render('test', array('class' => 'test')));
    }

}
