<?php

namespace TwbBundleTest;

/**
 * Test buttons dropdowns rendering
 * Based on http://getbootstrap.com/components/#btn-dropdowns
 */
class TwbBundleButtonsDropdownsTest extends \PHPUnit_Framework_TestCase {

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
        $this->expectedPath = __DIR__ . DIRECTORY_SEPARATOR . '../../_files/expected-buttons-dropdowns' . DIRECTORY_SEPARATOR;
        $oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
        $oRenderer = new \Zend\View\Renderer\PhpRenderer();
        $this->formButtonHelper = $oViewHelperPluginManager->get('formButton')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
    }

    /**
     * Test http://getbootstrap.com/components/#btn-dropdowns-single
     */
    public function testSingleButtonDropdowns() {
        $aDropDownOptions = array(
            'items' => array('Action', 'Another action', 'Something else here', \TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_DIVIDER, 'Separated link')
        );

        $sContent = '';
        $oButton = new \Zend\Form\Element\Button('default', array('label' => 'Default', 'dropdown' => $aDropDownOptions));
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('primary', array('label' => 'Primary', 'dropdown' => $aDropDownOptions));
        $oButton->setAttribute('class', 'btn-primary');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('success', array('label' => 'Success', 'dropdown' => $aDropDownOptions));
        $oButton->setAttribute('class', 'btn-success');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('info', array('label' => 'Info', 'dropdown' => $aDropDownOptions));
        $oButton->setAttribute('class', 'btn-info');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('warning', array('label' => 'Warning', 'dropdown' => $aDropDownOptions));
        $oButton->setAttribute('class', 'btn-warning');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('danger', array('label' => 'Danger', 'dropdown' => $aDropDownOptions));
        $oButton->setAttribute('class', 'btn-danger');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'dropdowns-single.phtml', $sContent);
    }

    /**
     * Test http://getbootstrap.com/components/#btn-dropdowns-split
     */
    public function testSplitButtonDropdowns() {
        $aDropDownOptions = array(
            'split' => true,
            'items' => array('Action', 'Another action', 'Something else here', \TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_DIVIDER, 'Separated link')
        );

        $sContent = '';

        $oButton = new \Zend\Form\Element\Button('default', array('label' => 'Default', 'dropdown' => $aDropDownOptions));
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('primary', array('label' => 'Primary', 'dropdown' => $aDropDownOptions));
        $oButton->setAttribute('class', 'btn-primary');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('success', array('label' => 'Success', 'dropdown' => $aDropDownOptions));
        $oButton->setAttribute('class', 'btn-success');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('info', array('label' => 'Info', 'dropdown' => $aDropDownOptions));
        $oButton->setAttribute('class', 'btn-info');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('warning', array('label' => 'Warning', 'dropdown' => $aDropDownOptions));
        $oButton->setAttribute('class', 'btn-warning');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $oButton = new \Zend\Form\Element\Button('danger', array('label' => 'Danger', 'dropdown' => $aDropDownOptions));
        $oButton->setAttribute('class', 'btn-danger');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'dropdowns-split.phtml', $sContent);
    }

    /**
     * Test http://getbootstrap.com/components/#btn-dropdowns-sizing
     */
    public function testDropdownsSizing() {
        $aDropDownOptions = array(
            'items' => array('Action', 'Another action', 'Something else here', \TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_DIVIDER, 'Separated link')
        );

        $sContent = '';

        //Large
        $oButton = new \Zend\Form\Element\Button('large-button-default', array('label' => 'Large button', 'dropdown' => $aDropDownOptions));
        $oButton->setAttribute('class', 'btn-lg');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        //Small
        $oButton = new \Zend\Form\Element\Button('small-button-default', array('label' => 'Small button', 'dropdown' => $aDropDownOptions));
        $oButton->setAttribute('class', 'btn-sm');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        //Extra small
        $oButton = new \Zend\Form\Element\Button('extra-small-button-default', array('label' => 'Extra small button', 'dropdown' => $aDropDownOptions));
        $oButton->setAttribute('class', 'btn-xs');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'dropdowns-sizing.phtml', $sContent);
    }

    /**
     * Test http://getbootstrap.com/components/#btn-dropdowns-dropup
     */
    public function testDropup() {
        $aDropDownOptions = array(
            'dropup' => true,
            'split' => true,
            'items' => array('Action', 'Another action', 'Something else here', \TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_DIVIDER, 'Separated link')
        );

        $sContent = '';

        $oButton = new \Zend\Form\Element\Button('default', array('label' => 'Dropup', 'dropdown' => $aDropDownOptions));
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        $aDropDownOptions['list_attributes'] = array('class' => 'pull-right');
        $oButton = new \Zend\Form\Element\Button('primary', array('label' => 'Right dropup', 'dropdown' => $aDropDownOptions));
        $oButton->setAttribute('class', 'btn-primary');
        $sContent .= $this->formButtonHelper->__invoke($oButton) . PHP_EOL;

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'dropup.phtml', $sContent);
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
