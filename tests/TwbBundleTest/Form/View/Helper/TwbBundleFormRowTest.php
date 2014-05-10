<?php

namespace TwbBundleTest\Form\View\Helper;

class TwbBundleFormRowTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var string
     */
    protected $expectedPath;

    /**
     * @var \TwbBundle\Form\View\Helper\TwbBundleFormRow
     */
    protected $formRowHelper;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp() {
        $this->expectedPath = __DIR__ . DIRECTORY_SEPARATOR . '../../../../_files/expected-rows' . DIRECTORY_SEPARATOR;
        $oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
        $oRenderer = new \Zend\View\Renderer\PhpRenderer();
        $oRenderer->setResolver(\TwbBundleTest\Bootstrap::getServiceManager()->get('ViewResolver'));
        $this->formRowHelper = $oViewHelperPluginManager->get('formRow')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
        $this->formRowHelper->setPartial(null);
    }

    public function testRenderPartial() {
        $this->formRowHelper->setPartial('partial-row');

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'partial.phtml', $this->formRowHelper->render(new \Zend\Form\Element('test-element')));
    }

    public function testRenderAddOnWithValidationStateAndDefinedLabelClass() {
        $oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormRow');
        $oReflectionMethod = $oReflectionClass->getMethod('renderElement');
        $oReflectionMethod->setAccessible(true);

        $oElement = new \Zend\Form\Element('test-element', array('validation-state' => 'warning'));
        $oElement
                ->setLabel('test-label')
                ->setLabelAttributes(array('class' => 'test-label-class'));

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'add-on-validation-states.phtml', $oReflectionMethod->invoke($this->formRowHelper, $oElement));
    }

    public function testRenderAddOnWithInlineLayoutAndDefinedLabelClass() {
        $oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormRow');
        $oReflectionMethod = $oReflectionClass->getMethod('renderElement');
        $oReflectionMethod->setAccessible(true);

        $oElement = new \Zend\Form\Element('test-element', array('twb-layout' => \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE));
        $oElement
                ->setLabel('test-label')
                ->setLabelAttributes(array('class' => 'test-label-class'));

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'add-on-inline-layout.phtml', $oReflectionMethod->invoke($this->formRowHelper, $oElement));
    }

    public function testRenderAddOnWithHorizontalLayoutAndDefinedLabelClass() {
        $oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormRow');
        $oReflectionMethod = $oReflectionClass->getMethod('renderElement');
        $oReflectionMethod->setAccessible(true);

        $oElement = new \Zend\Form\Element('test-element', array('twb-layout' => \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL));
        $oElement
                ->setLabel('test-label')
                ->setLabelAttributes(array('class' => 'test-label-class'));

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'add-on-horizontal-layout.phtml', $oReflectionMethod->invoke($this->formRowHelper, $oElement));
    }

    /**
     * @expectedException \DomainException
     */
    public function testRenderAddOnWithWrongLayout() {
        $oReflectionClass = new \ReflectionClass('\TwbBundle\Form\View\Helper\TwbBundleFormRow');
        $oReflectionMethod = $oReflectionClass->getMethod('renderElement');
        $oReflectionMethod->setAccessible(true);
        $oReflectionMethod->invoke($this->formRowHelper, new \Zend\Form\Element('test-element', array('label' => 'test-label', 'twb-layout' => 'wrong')));
    }

    public function testRenderErrorsWithInputErrorClass() {
        $this->formRowHelper->setInputErrorClass('input-error');
        $oElement = new \Zend\Form\Element\Text('input-text');
        $oElement->setAttribute('class', 'test-class');
        $oElement->setMessages(array(
            'This is an error message',
            'This is an another one error message'
        ));

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'errors-input-errors-class.phtml', $this->formRowHelper->__invoke($oElement));
    }

    public function testRenderErrorsWithoutInputErrorClass() {
        $this->formRowHelper->setInputErrorClass('input-error');
        $oElement = new \Zend\Form\Element\Text('input-text');
        $oElement->setMessages(array(
            'This is an error message',
            'This is an another one error message'
        ));

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'errors-without-input-errors-class.phtml', $this->formRowHelper->__invoke($oElement));
    }

    public function testRenderHiddenElement() {
        $this->formRowHelper->setInputErrorClass('input-error');
        $oElement = new \Zend\Form\Element\Hidden('input-hidden');
        //Test content
        $this->assertEquals(
                '<input type="hidden" name="input-hidden" class="form-control" value="">', $this->formRowHelper->__invoke($oElement)
        );

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'hidden-element.phtml', $this->formRowHelper->__invoke($oElement));
    }

    public function testRendeCheckboxWithDefinedLabelAttributes() {
        $oElement = new \Zend\Form\Element\Checkbox('test-checkbox');
        $oElement->setLabel('Test checkbox');
        $this->formRowHelper->setLabelAttributes(array('class' => 'test-class'));

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'checkbox-defined-label-attributes.phtml', $this->formRowHelper->__invoke($oElement));
    }

    public function testRendeRadiosWithHorizontalLayout() {
        $oElement = new \Zend\Form\Element\Radio('test-radio', array(
            'twb-layout' => \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL,
            'label' => 'Test radio',
            'column-size' => 'sm-10',
            'label_attributes' => array('class' => 'col-sm-2'),
            'value_options' => array(
                'option1' => 'Option one',
                'option2' => 'Option two'
            )
        ));

        //Test content
        $this->assertStringEqualsFile($this->expectedPath . 'radio-horizontal-layout.phtml', $this->formRowHelper->__invoke($oElement));
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
