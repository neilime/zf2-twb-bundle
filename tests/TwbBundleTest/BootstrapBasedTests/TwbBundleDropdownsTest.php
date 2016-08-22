<?php

namespace TwbBundleTest;

/**
 * Test dropdowns rendering
 * Based on http://getbootstrap.com/components/#dropdowns
 */
class TwbBundleDropdownsTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var string
     */
    protected $expectedPath;

    /**
     * @var \TwbBundle\View\Helper\TwbBundleDropDown
     */
    protected $dropdownHelper;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $this->expectedPath = __DIR__ . DIRECTORY_SEPARATOR . '../../_files/expected-dropdowns' . DIRECTORY_SEPARATOR;
        $oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('ViewHelperManager');
        $oRenderer = new \Zend\View\Renderer\PhpRenderer();
        $this->dropdownHelper = $oViewHelperPluginManager->get('dropDown')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
    }

    /**
     * Test http://getbootstrap.com/components/#dropdowns-example
     */
    public function testExample()
    {
        $aDropDownOptions = array(
            'label' => 'Dropdown',
            'name' => 'dropdownMenu1',
            'attributes' => array('class' => 'clearfix'),
            'list_attributes' => array('aria-labelledby' => 'dropdownMenu1'),
            'items' => array('Action', 'Another action', 'Something else here', \TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_DIVIDER, 'Separated link')
        );
        $sContent = $this->dropdownHelper->__invoke($aDropDownOptions);

        // Test content
        $this->assertStringEqualsFile($this->expectedPath . 'exemple.phtml', str_replace(PHP_EOL, "\n", $sContent));
    }

    /**
     * Test http://getbootstrap.com/components/#dropdowns-alignment
     */
    public function testAlignment()
    {
        $aDropDownOptions = array(
            'label' => 'Dropdown',
            'name' => 'dropdownMenu1',
            'attributes' => array('class' => 'clearfix'),
            'list_attributes' => array('aria-labelledby' => 'dropdownMenu1', 'class' => 'pull-right'),
            'items' => array('Action', 'Another action', 'Something else here', \TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_DIVIDER, 'Separated link')
        );
        $sContent = $this->dropdownHelper->__invoke($aDropDownOptions);

        // Test content
        $this->assertStringEqualsFile($this->expectedPath . 'alignment.phtml', str_replace(PHP_EOL, "\n", $sContent));
    }

    /**
     * Test http://getbootstrap.com/components/#dropdowns-headers
     */
    public function testHeaders()
    {
        $aDropDownOptions = array(
            'label' => 'Dropdown',
            'name' => 'dropdownMenu1',
            'attributes' => array('class' => 'clearfix'),
            'list_attributes' => array('aria-labelledby' => 'dropdownMenu1'),
            'items' => array(
                array('type' => \TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_HEADER, 'label' => 'Dropdown header'),
                'Action', 'Another action', 'Something else here',
                \TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_DIVIDER,
                array('type' => \TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_HEADER, 'label' => 'Dropdown header'),
                'Separated link'
            )
        );
        $sContent = $this->dropdownHelper->__invoke($aDropDownOptions);

        // Test content
        $this->assertStringEqualsFile($this->expectedPath . 'headers.phtml', str_replace(PHP_EOL, "\n", $sContent));
    }

    /**
     * Test http://getbootstrap.com/components/#dropdowns-disabled
     */
    public function testDisabled()
    {
        $aDropDownOptions = array(
            'label' => 'Dropdown',
            'name' => 'dropdownMenu1',
            'attributes' => array('class' => 'clearfix'),
            'list_attributes' => array('aria-labelledby' => 'dropdownMenu1'),
            'items' => array(
                'Regular link',
                'Disabled link' => array('attributes' => array('class' => 'disabled')),
                'Another link'
            )
        );
        $sContent = $this->dropdownHelper->__invoke($aDropDownOptions);

        // Test content
        $this->assertStringEqualsFile($this->expectedPath . 'disabled.phtml', str_replace(PHP_EOL, "\n", $sContent));
    }
}
