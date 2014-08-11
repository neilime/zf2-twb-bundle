<?php

namespace TwbBundleTest\Form\View\Helper;

class TwbBundleFormButtonTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \TwbBundle\Form\View\Helper\TwbBundleFormButton
     */
    protected $formButtonHelper;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp() {
        $oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
        $oRenderer = new \Zend\View\Renderer\PhpRenderer();
        $this->formButtonHelper = $oViewHelperPluginManager->get('formButton')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
    }

    /**
     * @expectedException \DomainException
     */
    public function testRenderElementWithEmptyButtonContentandLabel() {
        $this->formButtonHelper->render(new \Zend\Form\Element(null, array('dropdown' => array('test'))));
    }

    public function testRenderWithUndefinedButtonClass() {
        $oElement = new \Zend\Form\Element('test', array('label' => 'test'));
        $oElement->setAttribute('class', 'test');
        $this->assertEquals('<button name="test" class="test&#x20;btn&#x20;btn-default" type="submit" value="">test</button>', $this->formButtonHelper->render($oElement));
    }

    /**
     * @expectedException \LogicException
     */
    public function testRenderWithWrongTypeGlyphiconOption() {
        $this->formButtonHelper->render(new \Zend\Form\Element('test', array('glyphicon' => new \stdClass())));
    }

    /**
     * @expectedException \LogicException
     */
    public function testRenderWithWrongTypeGlyphiconIconOption() {
        $this->formButtonHelper->render(new \Zend\Form\Element('test', array('glyphicon' => array('icon' => new \stdClass()))));
    }

    public function testRenderWithEmptyGlyphiconPositionOption() {
        $this->assertEquals(
                '<button name="test" class="btn&#x20;btn-default" type="submit" value=""><span class="glyphicon&#x20;glyphicon-test"></span></button>', $this->formButtonHelper->render(new \Zend\Form\Element('test', array('glyphicon' => array('icon' => 'test'))))
        );
    }

    public function testRenderWithEmptyFontAwesomePositionOption() {
        $this->assertEquals(
                '<button name="test" class="btn&#x20;btn-default" type="submit" value=""><span class="fa&#x20;fa-test"></span></button>', $this->formButtonHelper->render(new \Zend\Form\Element('test', array('fontAwesome' => array('icon' => 'test'))))
        );
    }

    /**
     * @expectedException \LogicException
     */
    public function testRenderWithWrongTypeGlyphiconPositionOption() {
        $this->formButtonHelper->render(new \Zend\Form\Element('test', array('glyphicon' => array('icon' => 'test', 'position' => new \stdClass()))));
    }

    /**
     * @expectedException \LogicException
     */
    public function testRenderWithWrongGlyphiconPositionOption() {
        $this->formButtonHelper->render(new \Zend\Form\Element('test', array('glyphicon' => array('icon' => 'test', 'position' => 'wrong'))));
    }

    public function testRenderWithAppendGlyphiconPositionOption() {
        $this->assertEquals(
                '<button name="test" class="btn&#x20;btn-default" type="submit" value="">test <span class="glyphicon&#x20;glyphicon-test"></span></button>', $this->formButtonHelper->render(new \Zend\Form\Element('test', array('label' => 'test', 'glyphicon' => array('icon' => 'test', 'position' => \TwbBundle\Form\View\Helper\TwbBundleFormButton::GLYPHICON_APPEND))))
        );
    }

    /**
     * @expectedException \LogicException
     */
    public function testRenderWithWrongTypeDropdownOption() {
        $this->formButtonHelper->render(new \Zend\Form\Element('test', array('label' => 'test', 'dropdown' => new \stdClass())));
    }

}
