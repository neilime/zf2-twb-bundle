<?php

namespace TwbBundleTest;

/**
 * Test buttons rendering
 * Based on http://getbootstrap.com/css/#buttons
 */
class TwbBundleButtonsTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var string
     */
    protected $expectedPath;

    /**
     * @var \TwbBundle\Form\View\Helper\TwbBundleForm
     */
    protected $formButtonHelper;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp() {
        $this->expectedPath = __DIR__ . DIRECTORY_SEPARATOR . '../../_files/expected-buttons' . DIRECTORY_SEPARATOR;
        $oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
        $oRenderer = new \Zend\View\Renderer\PhpRenderer();
        $this->formButtonHelper = $oViewHelperPluginManager->get('formButton')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
    }

    /**
     * Test http://getbootstrap.com/css/#buttons-options
     */
    public function testButtonsOptions() {
        $sContent = '';
        $oButton = new \Zend\Form\Element\Button('default', array('label' => 'Default'));
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('primary', array('label' => 'Primary'));
        $oButton->setAttribute('class', 'btn-primary');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('success', array('label' => 'Success'));
        $oButton->setAttribute('class', 'btn-success');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('info', array('label' => 'Info'));
        $oButton->setAttribute('class', 'btn-info');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('warning', array('label' => 'Warning'));
        $oButton->setAttribute('class', 'btn-warning');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('danger', array('label' => 'Danger'));
        $oButton->setAttribute('class', 'btn-danger');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('link', array('label' => 'Link'));
        $oButton->setAttribute('class', 'btn-link');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'options.phtml', $sContent);
    }

    /**
     * Test http://getbootstrap.com/css/#buttons-sizes
     */
    public function testButtonsSizes() {
        $sContent = '';

        //Large
        $oButton = new \Zend\Form\Element\Button('large-button-primary', array('label' => 'Large button'));
        $oButton->setAttribute('class', 'btn-primary btn-lg');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('large-button-default', array('label' => 'Large button'));
        $oButton->setAttribute('class', 'btn-lg');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        //Default
        $oButton = new \Zend\Form\Element\Button('button-primary', array('label' => 'Default button'));
        $oButton->setAttribute('class', 'btn-primary');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('button-default', array('label' => 'Default button'));
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        //Small
        $oButton = new \Zend\Form\Element\Button('small-button-primary', array('label' => 'Small button'));
        $oButton->setAttribute('class', 'btn-primary btn-sm');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('small-button-default', array('label' => 'Small button'));
        $oButton->setAttribute('class', 'btn-sm');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        //Extra small
        $oButton = new \Zend\Form\Element\Button('extra-small-button-primary', array('label' => 'Extra small button'));
        $oButton->setAttribute('class', 'btn-primary btn-xs');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('extra-small-button-default', array('label' => 'Extra small button'));
        $oButton->setAttribute('class', 'btn-xs');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        //Block level
        $oButton = new \Zend\Form\Element\Button('block-level-button-primary', array('label' => 'Block level button'));
        $oButton->setAttribute('class', 'btn-primary btn-block');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('block-level-button-default', array('label' => 'Block level button'));
        $oButton->setAttribute('class', 'btn-block');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'sizes.phtml', $sContent);
    }

    /**
     * Test http://getbootstrap.com/css/#buttons-active
     */
    public function testButtonsActive() {
        $sContent = '';

        $oButton = new \Zend\Form\Element\Button('large-button-primary-active', array('label' => 'Primary button'));
        $oButton->setAttributes(array(
            'class' => 'btn-primary btn-lg active',
        ));
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('large-button-default-active', array('label' => 'Button'));
        $oButton->setAttributes(array(
            'class' => 'btn-lg active',
        ));
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'active.phtml', $sContent);
    }

    /**
     * Test http://getbootstrap.com/css/#buttons-disabled
     */
    public function testButtonsDisabled() {
        $sContent = '';

        $oButton = new \Zend\Form\Element\Button('large-button-primary-disabled', array('label' => 'Primary button'));
        $oButton->setAttributes(array(
            'class' => 'btn-primary btn-lg',
            'disabled' => true
        ));
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('large-button-default-disabled', array('label' => 'Button'));
        $oButton->setAttributes(array(
            'class' => 'btn-lg',
            'disabled' => true
        ));
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'disabled.phtml', $sContent);
    }

    /**
     * @param string $sExpectedFile
     * @param string $sActualString
     * @param string $sMessage
     * @param boolean $bCanonicalize
     * @param boolean $bIgnoreCase
     */
    public static function assertStringEqualsFile($sExpectedFile, $sActualString, $sMessage = '', $bCanonicalize = false, $bIgnoreCase = false) {
        return parent::assertStringEqualsFile($sExpectedFile, $sActualString, $sMessage, $bCanonicalize, $bIgnoreCase);
    }

}
