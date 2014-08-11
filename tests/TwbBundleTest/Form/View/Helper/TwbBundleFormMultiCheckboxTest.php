<?php

namespace TwbBundleTest\Form\View\Helper;

class TwbBundleFormMultiCheckboxTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \TwbBundle\Form\View\Helper\TwbBundleFormMultiCheckbox
     */
    protected $formMultiCheckboxHelper;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp() {
        $oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
        $oRenderer = new \Zend\View\Renderer\PhpRenderer();
        $this->formMultiCheckboxHelper = $oViewHelperPluginManager->get('formMultiCheckbox')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
    }

    public function testRenderWithNoInline() {
        $oElement = new \Zend\Form\Element\MultiCheckbox('test-element', array('inline' => false, 'value_options' => array('test-option')));
        $this->formMultiCheckboxHelper->render($oElement);
        $this->assertEquals(array('class' => 'checkbox'), $oElement->getLabelAttributes());
    }

}
