<?php

namespace TwbBundleTest\Form\View\Helper;

class TwbBundleFormElementTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \TwbBundle\Form\View\Helper\TwbBundleFormElement
     */
    protected $formElementHelper;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp() {
        $oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
        $oRenderer = new \Zend\View\Renderer\PhpRenderer();
        $this->formElementHelper = $oViewHelperPluginManager->get('formElement')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRenderAddOnWithEmptuOption() {
        $oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormElement');
        $oReflectionMethod = $oReflectionClass->getMethod('renderAddOn');
        $oReflectionMethod->setAccessible(true);
        $oReflectionMethod->invoke($this->formElementHelper, '');
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRenderAddOnWithWrongTypeOption() {
        $oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormElement');
        $oReflectionMethod = $oReflectionClass->getMethod('renderAddOn');
        $oReflectionMethod->setAccessible(true);
        $oReflectionMethod->invoke($this->formElementHelper, new \stdClass());
    }

    public function testRenderAddOnWithoutTranslator() {
        $oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormElement');
        $oReflectionMethod = $oReflectionClass->getMethod('renderAddOn');
        $oReflectionMethod->setAccessible(true);

        //Unset tranlator
        $this->assertSame($this->formElementHelper, $this->formElementHelper->setTranslator(null));
        $this->assertFalse($this->formElementHelper->hasTranslator());

        $this->assertEquals('<span class="input-group-addon">test</span>', $oReflectionMethod->invoke($this->formElementHelper, 'test'));

        //Set translator
        $this->assertSame($this->formElementHelper, $this->formElementHelper->setTranslator(\TwbBundleTest\Bootstrap::getServiceManager()->get('MVCTranslator')));
        $this->assertTrue($this->formElementHelper->hasTranslator());
    }

    public function testRenderAddOnWithElementAsArray() {
        $oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormElement');
        $oReflectionMethod = $oReflectionClass->getMethod('renderAddOn');
        $oReflectionMethod->setAccessible(true);
        $this->assertEquals(
                '<span class="input-group-addon"><input name="test-element" class="form-control" type="text" value=""></span>', $oReflectionMethod->invoke($this->formElementHelper, array('element' => array('name' => 'test-element')))
        );
    }

    /**
     * @expectedException \LogicException
     */
    public function testRenderAddOnWithWrongTypeElement() {
        $oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormElement');
        $oReflectionMethod = $oReflectionClass->getMethod('renderAddOn');
        $oReflectionMethod->setAccessible(true);
        $oReflectionMethod->invoke($this->formElementHelper, array('element' => new \stdClass()));
    }

    /**
     * @expectedException \LogicException
     */
    public function testRenderAddOnWithWrongTypeText() {
        $oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormElement');
        $oReflectionMethod = $oReflectionClass->getMethod('renderAddOn');
        $oReflectionMethod->setAccessible(true);
        $oReflectionMethod->invoke($this->formElementHelper, array('text' => new \stdClass()));
    }

    public function testSetTranslatorEnabled() {
        $this->assertSame($this->formElementHelper, $this->formElementHelper->setTranslatorEnabled(false));
        $this->assertFalse($this->formElementHelper->isTranslatorEnabled());

        $this->assertSame($this->formElementHelper, $this->formElementHelper->setTranslatorEnabled(true));
        $this->assertTrue($this->formElementHelper->isTranslatorEnabled());
    }

    public function testSetTranslatorWithTextDomain() {
        $this->assertSame($this->formElementHelper, $this->formElementHelper->setTranslator($this->formElementHelper->getTranslator(), 'test'));
        $this->assertEquals('test', $this->formElementHelper->getTranslatorTextDomain());
    }

    public function testSetTranslatorTextDomain() {
        $this->assertSame($this->formElementHelper, $this->formElementHelper->setTranslatorTextDomain('test'));
        $this->assertEquals('test', $this->formElementHelper->getTranslatorTextDomain());

        $this->assertSame($this->formElementHelper, $this->formElementHelper->setTranslatorTextDomain());
        $this->assertEquals('default', $this->formElementHelper->getTranslatorTextDomain());
    }

}
