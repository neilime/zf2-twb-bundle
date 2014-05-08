<?php

namespace TwbBundleTest\Form\View\Helper;

class TwbBundleFormCollectionTest extends \PHPUnit_Framework_TestCase {

    /**
     * @var \TwbBundle\Form\View\Helper\TwbBundleFormCollection
     */
    protected $formCollectionHelper;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp() {
        $oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
        $oRenderer = new \Zend\View\Renderer\PhpRenderer();
        $this->formCollectionHelper = $oViewHelperPluginManager->get('formCollection')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
    }

    public function testRenderWithPluginFunctionUnavailable() {
        $this->formCollectionHelper->setView(new \Zend\View\Renderer\FeedRenderer());
        $this->assertEquals('', $this->formCollectionHelper->render(new \Zend\Form\Element\Collection(null, array('label' => 'test-element'))));
    }

    public function testRenderWithShouldWrap() {
        $this->formCollectionHelper->setShouldWrap(true);
        $this->assertEquals(
                '<fieldset><legend>test-element</legend></fieldset>', $this->formCollectionHelper->render(new \Zend\Form\Element\Collection(null, array('label' => 'test-element')))
        );
    }

    public function testRenderWithShouldCreateTemplate() {
        $oElement = new \Zend\Form\Element('test');
        $oForm = new \Zend\Form\Form();
        $oForm->add(array(
            'name' => 'test-collection',
            'type' => 'Zend\Form\Element\Collection',
            'options' => array(
                'should_create_template' => true,
                'target_element' => $oElement
            )
        ));
        $this->assertEquals(
                '<fieldset><span data-template="DATA_TEMPLATE"></span></fieldset>', preg_replace('/<span data-template="[^"]+"><\/span>/', '<span data-template="DATA_TEMPLATE"></span>', $this->formCollectionHelper->render($oForm->get('test-collection')))
        );
    }

    public function testRenderInlineFieldsetWithAlreadyDefinedClass() {
        $oFieldset = new \Zend\Form\Fieldset('inline-fieldset', array('twb-layout' => \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE));
        $oFieldset->setAttributes(array('id' => 'inline-fieldset', 'class' => 'test-class'));

        $oFieldset->add(array(
            'name' => 'input-one',
            'attributes' => array('placeholder' => 'input-one'),
            'options' => array('label' => '')
        ))->add(array(
            'name' => 'input-two',
            'attributes' => array('placeholder' => 'input-two'),
            'options' => array('label' => '')
        ));

        $oCollection = new \Zend\Form\Element\Collection('inline-collection', array(
            'twb-layout' => \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE
        ));
        $oCollection->add($oFieldset)->setAttributes(array('id' => 'inline-collection'));

        $oForm = new \Zend\Form\Form();
        $oForm->add($oCollection);

        $this->assertStringEqualsFile(
                __DIR__ . DIRECTORY_SEPARATOR . '../../../../_files/expected-fieldsets/inline-fieldset.html', $this->formCollectionHelper->__invoke($oForm->get('inline-collection'), false)
        );
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
