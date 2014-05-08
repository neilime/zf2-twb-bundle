<?php

namespace TwbBundleTest;

/**
 * Test button groups rendering
 * Based on http://getbootstrap.com/components/#btn-groups
 */
class TwbBundleButtonGroupsTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var string
     */
    protected $expectedPath;

    /**
     * @var \TwbBundle\View\Helper\TwbBundleButtonGroup
     */
    protected $buttonGroupHelper;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp() {
        $this->expectedPath = __DIR__ . DIRECTORY_SEPARATOR . '../../_files/expected-button-groups' . DIRECTORY_SEPARATOR;
        $oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
        $oRenderer = new \Zend\View\Renderer\PhpRenderer();
        $this->buttonGroupHelper = $oViewHelperPluginManager->get('buttonGroup')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
    }

    /**
     * Test http://getbootstrap.com/components/#btn-groups-single
     */
    public function testBasicExemple() {
        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'single.phtml', $this->buttonGroupHelper->__invoke(array(
                    new \Zend\Form\Element\Button('left', array('label' => 'Left')),
                    new \Zend\Form\Element\Button('middle', array('label' => 'Middle')),
                    new \Zend\Form\Element\Button('right', array('label' => 'Right')),
        )));
    }

    /**
     * Test http://getbootstrap.com/components/#btn-groups-toolbar
     */
    public function testButtonToolbar() {

        $sContent = '<div class="btn-toolbar" role="toolbar">';

        //First group
        $sContent .= $this->buttonGroupHelper->__invoke(array(
            new \Zend\Form\Element\Button('1', array('label' => '1')),
            new \Zend\Form\Element\Button('2', array('label' => '2')),
            new \Zend\Form\Element\Button('3', array('label' => '3')),
            new \Zend\Form\Element\Button('4', array('label' => '4')),
        ));

        //Second group
        $sContent .= $this->buttonGroupHelper->__invoke(array(
            new \Zend\Form\Element\Button('5', array('label' => '5')),
            new \Zend\Form\Element\Button('6', array('label' => '6')),
            new \Zend\Form\Element\Button('7', array('label' => '7')),
        ));

        //Third group
        $sContent .= $this->buttonGroupHelper->__invoke(array(
            new \Zend\Form\Element\Button('8', array('label' => '8')),
        ));

        $sContent .= '</div>';

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'toolbar.phtml', $sContent);
    }

    /**
     * Test http://getbootstrap.com/components/#btn-groups-sizing
     */
    public function testSizing() {

        $sContent = '';

        //Large
        $sContent .= '<div class="btn-toolbar" role="toolbar">' . $this->buttonGroupHelper->__invoke(array(
                    new \Zend\Form\Element\Button('left', array('label' => 'Left')),
                    new \Zend\Form\Element\Button('middle', array('label' => 'Middle')),
                    new \Zend\Form\Element\Button('right', array('label' => 'Right')),
                        ), array('attributes' => array('class' => 'btn-group-lg'))) . '</div>';

        //Normal
        $sContent .= '<div class="btn-toolbar" role="toolbar">' . $this->buttonGroupHelper->__invoke(array(
                    new \Zend\Form\Element\Button('left', array('label' => 'Left')),
                    new \Zend\Form\Element\Button('middle', array('label' => 'Middle')),
                    new \Zend\Form\Element\Button('right', array('label' => 'Right')),
                )) . '</div>';

        //Small
        $sContent .= '<div class="btn-toolbar" role="toolbar">' . $this->buttonGroupHelper->__invoke(array(
                    new \Zend\Form\Element\Button('left', array('label' => 'Left')),
                    new \Zend\Form\Element\Button('middle', array('label' => 'Middle')),
                    new \Zend\Form\Element\Button('right', array('label' => 'Right')),
                        ), array('attributes' => array('class' => 'btn-group-sm'))) . '</div>';

        //Extra small
        $sContent .= '<div class="btn-toolbar" role="toolbar">' . $this->buttonGroupHelper->__invoke(array(
                    new \Zend\Form\Element\Button('left', array('label' => 'Left')),
                    new \Zend\Form\Element\Button('middle', array('label' => 'Middle')),
                    new \Zend\Form\Element\Button('right', array('label' => 'Right')),
                        ), array('attributes' => array('class' => 'btn-group-xs'))) . '</div>';

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'sizing.phtml', $sContent);
    }

    /**
     * Test http://getbootstrap.com/components/#btn-groups-nested
     */
    public function testNesting() {
        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'nested.phtml', $this->buttonGroupHelper->__invoke(array(
                    new \Zend\Form\Element\Button('1', array('label' => '1')),
                    new \Zend\Form\Element\Button('2', array('label' => '2')),
                    new \Zend\Form\Element\Button('dropdown', array('label' => 'Dropdown', 'dropdown' => array('items' => array('Dropdown link', 'Dropdown link'))))
        )));
    }

    /**
     * Test http://getbootstrap.com/components/#btn-groups-vertical
     */
    public function testVerticalVariation() {
        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'vertical.phtml', $this->buttonGroupHelper->__invoke(array(
                    new \Zend\Form\Element\Button('button', array('label' => 'Button')),
                    new \Zend\Form\Element\Button('button', array('label' => 'Button')),
                    new \Zend\Form\Element\Button('dropdown', array('label' => 'Dropdown', 'dropdown' => array('items' => array('Dropdown link', 'Dropdown link')))),
                    new \Zend\Form\Element\Button('button', array('label' => 'Button')),
                    new \Zend\Form\Element\Button('button', array('label' => 'Button')),
                    new \Zend\Form\Element\Button('dropdown', array('label' => 'Dropdown', 'dropdown' => array('items' => array('Dropdown link', 'Dropdown link')))),
                    new \Zend\Form\Element\Button('dropdown', array('label' => 'Dropdown', 'dropdown' => array('items' => array('Dropdown link', 'Dropdown link')))),
                    new \Zend\Form\Element\Button('dropdown', array('label' => 'Dropdown', 'dropdown' => array('items' => array('Dropdown link', 'Dropdown link')))),
                        ), array('attributes' => array('class' => 'btn-group-vertical'))));
    }

    /**
     * Test http://getbootstrap.com/components/#btn-groups-justified
     */
    public function testJustifiedButtonGroups() {
        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'justified.phtml', $this->buttonGroupHelper->__invoke(array(
                    new \Zend\Form\Element\Button('left', array('label' => 'Left')),
                    new \Zend\Form\Element\Button('middle', array('label' => 'Middle')),
                    new \Zend\Form\Element\Button('right', array('label' => 'Right')),
                        ), array('attributes' => array('class' => 'btn-group-justified'))));
    }

    /**
     * @param string $sExpectedFile
     * @param string $sActualString
     * @param string $sMessage
     * @param boolean $bCanonicalize
     * @param boolean $bIgnoreCase
     */
    public static function assertStringEqualsFile($sExpectedFile, $sActualString, $sMessage = '', $bCanonicalize = false, $bIgnoreCase = false) {
        file_put_contents($sExpectedFile, $sActualString);
        return parent::assertStringEqualsFile($sExpectedFile, $sActualString, $sMessage, $bCanonicalize, $bIgnoreCase);
    }

}
