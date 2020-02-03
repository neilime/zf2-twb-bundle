<?php

namespace TwbBundleTest\Form\View\Helper;

class TwbBundleFormErrorsTest extends \PHPUnit_Framework_TestCase {

    /**
     * Contains an instance of TwbBundleFormErrors.
     * @var \TwbBundle\Form\View\Helper\TwbBundleFormErrors
     */
    protected $formErrorsHelper = null;

    public function setUp() {
        $this->getFormErrorsHelper();
    }

    /**
     * Enforces that the correct helpers is being initialised.
     * @param \TwbBundle\Form\View\Helper\TwbBundleFormErrors $oFormErrorsHelper
     * @return \TwbBundleTest\Form\View\Helper\TwbBundleFormErrorsTest
     */
    public function setFormErrorsHelper(\TwbBundle\Form\View\Helper\TwbBundleFormErrors $oFormErrorsHelper = null) {
        $this->formErrorsHelper = $oFormErrorsHelper;
        return $this;
    }

    /**
     * Gets or initialises the correct helper for this test.
     * @return \TwbBundle\Form\View\Helper\TwbBundleFormErrors
     */
    public function getFormErrorsHelper() {
        if (null === $this->formErrorsHelper) {
            $oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('ViewHelperManager');
            $oRenderer = new \Laminas\View\Renderer\PhpRenderer();
            $oRenderer->setResolver(\TwbBundleTest\Bootstrap::getServiceManager()->get('ViewResolver'));
            $helper = $oViewHelperPluginManager->get('formErrors')
                    ->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));

            $this->setFormErrorsHelper($helper);
        }

        return $this->formErrorsHelper;
    }

    public function testInvokeWithoutFormReturnsObject() {
        $oHelper = $this->getFormErrorsHelper();
        $this->assertInstanceOf('TwbBundle\Form\View\Helper\TwbBundleFormErrors', $oHelper());
    }

    public function testInvokeWithFormCallsRender() {
        $form = $this->getMockBuilder('\Laminas\Form\Form')
            ->setMethods(array('hasValidated', 'isValid'))
            ->disableOriginalConstructor()
            ->getMock();
        $form->expects($this->exactly(1))
                ->method('hasValidated')
                ->will($this->returnValue(true));
        $form->expects($this->atLeastOnce())
                ->method('isValid')
                ->will($this->returnValue(false));

        $this->assertInstanceOf('\Laminas\Form\Form', $form);

        $helper = $this->getMockBuilder('TwbBundle\Form\View\Helper\TwbBundleFormErrors')
            ->setMethods(array('render'))
            ->getMock();
        $helper->expects($this->atLeastOnce())
                ->method('render')
                ->with($this->identicalTo($form), 'There were errors in the form submission', false /* default */)
                ->will($this->returnValue('return value'));

        $this->assertInstanceOf('TwbBundle\Form\View\Helper\TwbBundleFormErrors', $helper());

        $this->assertEquals('return value', $helper($form));
    }

    public function testInvokeWithFormNoErrorsReturnsNull() {
        $form = $this->getMockBuilder('\Laminas\Form\Form')
            ->setMethods(array('hasValidated', 'isValid'))
            ->disableOriginalConstructor()
            ->getMock();
        $form->expects($this->exactly(1))
                ->method('hasValidated')
                ->will($this->returnValue(true));
        $form->expects($this->atLeastOnce())
                ->method('isValid')
                ->will($this->returnValue(true));

        $this->assertInstanceOf('\Laminas\Form\Form', $form);

        $helper = $this->getFormErrorsHelper();
        $this->assertInstanceOf('TwbBundle\Form\View\Helper\TwbBundleFormErrors', $helper());

        $this->assertEquals(null, $helper($form));
    }

    public function testCorrectHtmlMarkupBasedOnErrorMessagesArray() {
        $helper = $this->getFormErrorsHelper();

        /**
         * Tes that we haventhe corect helper. Ensures that our test is setup correctly.
         */
        $this->assertInstanceOf('\TwbBundle\Form\View\Helper\TwbBundleFormErrors', $helper);

        $messagesArray = array(
            'firstName' => array(
                'isEmpty' => 'Value is required and can\'t be empty',
            ),
            'lastName' => array(
                'isEmpty' => 'Value is required and can\'t be empty',
            ),
            'category' => array(
                'isEmpty' => 'Value is required and can\'t be empty',
            ),
            'dob' => array(
                'dateInvalidDate' => 'The input does not appear to be a valid date',
            ),
        );

        $element = $this->getMockBuilder('\Laminas\Form\Element')
            ->setMethods(array('getAttribute', 'getLabel'))
            ->disableOriginalConstructor()
            ->getMock();

        $element->expects($this->atLeastOnce())
                ->method('getAttribute')
                ->with($this->equalTo('id'))
                ->will($this->returnValue('someId'));
        $element->expects($this->atLeastOnce())
                ->method('getLabel')
                ->will($this->onConsecutiveCalls('First Name', 'Last Name', 'Category'));

        $this->assertInstanceOf('\Laminas\Form\Element', $element);

        $element2 = $this->getMockBuilder('\Laminas\Form\Element')
            ->setMethods(array('getAttribute', 'getLabel'))
            ->disableOriginalConstructor()
            ->getMock();
        $element2->expects($this->atLeastOnce())
                ->method('getAttribute')
                ->with($this->equalTo('id'))
                ->will($this->returnValue(null));
        $element2->expects($this->atLeastOnce())
                ->method('getLabel')
                ->will($this->returnValue('Date of Birth'));

        $this->assertInstanceOf('\Laminas\Form\Element', $element2);

        $map = array(
            array('firstName', $element),
            array('lastName', $element),
            array('category', $element),
            array('dob', $element2),
        );

        $form = $this->getMockBuilder('\Laminas\Form\Form')
            ->setMethods(array('getMessages', 'get'))
            ->disableOriginalConstructor()
            ->getMock();
        $form->expects($this->exactly(1))
                ->method('getMessages')
                ->will($this->returnValue($messagesArray));
        $form->expects($this->atLeastOnce())
                ->method('get')
                ->will($this->returnValueMap($map));

        $this->assertInstanceOf('\Laminas\Form\Form', $form);

        $output = $helper->render($form, 'Errors below', false);

        $ex = '<div class="alert-danger&#x20;alert">'
                . '<h4>Errors below</h4><ul><li>'
                . '<a href="#someId">First Name: Value is required and can\'t be empty</a></li><li>'
                . '<a href="#someId">Last Name: Value is required and can\'t be empty</a></li><li>'
                . '<a href="#someId">Category: Value is required and can\'t be empty</a></li><li>'
                . 'Date of Birth: The input does not appear to be a valid date'
                . '</li></ul></div>';

        $this->assertEquals($ex, $output);
    }

}
