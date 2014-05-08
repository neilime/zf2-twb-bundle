<?php

namespace TwbBundleTest\View\Helper;

class TwbBundleDropDownTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \TwbBundle\View\Helper\TwbBundleDropDown
     */
    protected $dropDownHelper;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp() {
        $oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
        $oRenderer = new \Zend\View\Renderer\PhpRenderer();
        $this->dropDownHelper = $oViewHelperPluginManager->get('dropDown')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRenderToggleWithWrongTypeAttributes() {
        $this->dropDownHelper->renderToggle(array('toggle_attributes' => 'wrong'));
    }

    public function testRenderToggleWithEmptyClassAttribute() {
        $this->assertEquals('<a class="sr-only&#x20;dropdown-toggle" data-toggle="dropdown" role="button" href="&#x23;"> <b class="caret"></b></a>', $this->dropDownHelper->renderToggle(array('toggle_attributes' => array('class' => ''))));
    }

    public function testRenderToggleWithDefinedClassAttribute() {
        $this->assertEquals('<a class="test-toggle&#x20;sr-only&#x20;dropdown-toggle" data-toggle="dropdown" role="button" href="&#x23;"> <b class="caret"></b></a>', $this->dropDownHelper->renderToggle(array('toggle_attributes' => array('class' => 'test-toggle'))));
    }

    public function testRenderItemWithDefinedClassAttribute() {
        $oReflectionClass = new \ReflectionClass('\TwbBundle\View\Helper\TwbBundleDropDown');
        $oReflectionMethod = $oReflectionClass->getMethod('renderItem');
        $oReflectionMethod->setAccessible(true);

        //Header
        $this->assertEquals(
                '<li class="test-item&#x20;dropdown-header" role="presentation">test-label</li>', $oReflectionMethod->invoke($this->dropDownHelper, array(
                    'type' => \TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_HEADER,
                    'label' => 'test-label',
                    'attributes' => array('class' => 'test-item')
                ))
        );

        //Divider
        $this->assertEquals(
                '<li class="test-item&#x20;divider" role="presentation"></li>', $oReflectionMethod->invoke($this->dropDownHelper, array(
                    'type' => \TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_DIVIDER,
                    'attributes' => array('class' => 'test-item')
                ))
        );
    }

}
