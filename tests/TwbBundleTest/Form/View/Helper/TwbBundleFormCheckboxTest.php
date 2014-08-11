<?php

namespace TwbBundleTest\Form\View\Helper;

class TwbBundleFormCheckboxTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \TwbBundle\Form\View\Helper\TwbBundleFormCheckbox
     */
    protected $formCheckboxHelper;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp() {
        $oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
        $oRenderer = new \Zend\View\Renderer\PhpRenderer();
        $this->formCheckboxHelper = $oViewHelperPluginManager->get('formCheckbox')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testRenderElementWithWrongElement() {
        $this->formCheckboxHelper->render(new \Zend\Form\Element());
    }

    /**
     * @expectedException \LogicException
     */
    public function testRenderElementWithEmptyName() {
        $this->formCheckboxHelper->render(new \Zend\Form\Element\Checkbox(''));
    }

    public function testRenderWithLabelPrepend() {
        $this->assertEquals('<input type="hidden" name="prepend" value="0"><label>Prepend label <input type="checkbox" name="prepend" value="1"></label>', $this->formCheckboxHelper->render(new \Zend\Form\Element\Checkbox('prepend', array(
                    'label' => 'Prepend label',
                    'label_options' => array('position' => \Zend\Form\View\Helper\FormRow::LABEL_PREPEND)
        ))));
    }

    public function testRenderWithCheckedElement() {
        $oCheckbox = new \Zend\Form\Element\Checkbox('checked');
        $oCheckbox->setChecked(true);
        $this->assertEquals('<input type="hidden" name="checked" value="0"><input type="checkbox" name="checked" value="1" checked="checked">', $this->formCheckboxHelper->render($oCheckbox));
    }

}
