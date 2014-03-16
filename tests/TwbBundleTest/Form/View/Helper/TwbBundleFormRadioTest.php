<?php

namespace TwbBundleTest\Form\View\Helper;

class TwbBundleFormRadioTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \TwbBundle\Form\View\Helper\TwbBundleFormRadio
     */
    protected $formRadioHelper;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp() {
        $oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
        $oRenderer = new \Zend\View\Renderer\PhpRenderer();
        $this->formRadioHelper = $oViewHelperPluginManager->get('formRadio')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
    }

    public function testRenderOptionsWithPrependingLabel() {
        $oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormRadio');
        $oReflectionMethod = $oReflectionClass->getMethod('renderOptions');
        $oReflectionMethod->setAccessible(true);

        $this->formRadioHelper->setLabelPosition(\TwbBundle\Form\View\Helper\TwbBundleFormRadio::LABEL_PREPEND);
        $this->assertEquals(
                '<label>test<input value="0" checked="checked"></label>', $oReflectionMethod->invoke($this->formRadioHelper, new \Zend\Form\Element\Radio(), array('test'), array('test'), array())
        );
    }

    public function testRenderOptionsWithDefineAttributesId() {
        $oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormRadio');
        $oReflectionMethod = $oReflectionClass->getMethod('renderOptions');
        $oReflectionMethod->setAccessible(true);

        $this->formRadioHelper->setLabelPosition(\TwbBundle\Form\View\Helper\TwbBundleFormRadio::LABEL_PREPEND);
        $this->assertEquals(
                '<label>test1<input id="test_id" value="0" checked="checked"></label></div><div class="radio"><label>test2<input value="1"></label>', $oReflectionMethod->invoke($this->formRadioHelper, new \Zend\Form\Element\Radio(), array('test1', 'test2'), array('test'), array('id' => 'test_id'))
        );
    }

    public function testRenderOptionsWithOptionsSpecs() {
        $oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormRadio');
        $oReflectionMethod = $oReflectionClass->getMethod('renderOptions');
        $oReflectionMethod->setAccessible(true);

        $this->formRadioHelper->setLabelPosition(\TwbBundle\Form\View\Helper\TwbBundleFormRadio::LABEL_PREPEND);
        $this->assertEquals(
                '<label>test1<input id="test_id" type="radio" value="0" checked="checked"></label></div><div class="radio"><label class="test-label-class">test2<input type="radio" class="test-class" value="" checked="checked" disabled="disabled"></label>', $oReflectionMethod->invoke($this->formRadioHelper, new \Zend\Form\Element\Radio(), array('test1', array('label' => 'test2', 'selected' => true, 'disabled' => true, 'label_attributes' => array('class' => 'test-label-class'), 'attributes' => array('class' => 'test-class'))), array('test'), array('id' => 'test_id', 'type' => 'radio'))
        );
    }

}
