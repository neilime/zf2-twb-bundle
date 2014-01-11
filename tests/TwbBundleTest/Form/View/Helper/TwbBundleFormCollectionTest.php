<?php
namespace TwbBundleTest\Form\View\Helper;
class TwbBundleFormCollectionTest extends \PHPUnit_Framework_TestCase{
	/**
	 * @var \TwbBundle\Form\View\Helper\TwbBundleFormCollection
	 */
	protected $formCollectionHelper;

	/**
	 * @see \PHPUnit_Framework_TestCase::setUp()
	 */
	public function setUp(){
		$oViewHelperPluginManager = \TwbBundleTest\Bootstrap::getServiceManager()->get('view_helper_manager');
		$oRenderer = new \Zend\View\Renderer\PhpRenderer();
		$this->formCollectionHelper = $oViewHelperPluginManager->get('formCollection')->setView($oRenderer->setHelperPluginManager($oViewHelperPluginManager));
	}

	public function testRenderWithShoulddWrap(){
		$this->formCollectionHelper->setShouldWrap(true);
		$this->assertEquals(
			'<fieldset ><legend >test-element</legend></fieldset>',
			$this->formCollectionHelper->render(new \Zend\Form\Element(null,array('label' => 'test-element')))
		);
	}

    public function testRenderInlineFieldsetWithAlreadyDefinedClass(){
        $oFieldset = new \Zend\Form\Fieldset('inline-fieldset',array('twb-layout' => \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE));
        $oFieldset->setAttribute('class', 'test-class');

        $oFieldset->add(array(
            'name' => 'input-one',
            'attributes' => array('placeholder' => 'input-one'),
            'options' => array('label' => '')
        ))->add(array(
            'name' => 'input-two',
            'attributes' => array('placeholder' => 'input-two'),
            'options' => array('label' => '')
        ));

        $oForm = new \Zend\Form\Form();
        $oForm->add(array(
            'type' => 'Zend\Form\Element\Collection',
            'name' => 'inline-fieldset',
            'twb-layout' => \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE,
            'options' => array(
                'twb-layout' => \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE,
                'should_create_template' => true,
                'allow_add' => true,
                'target_element' => $oFieldset
            )
        ));

        $this->assertStringEqualsFile(
			__DIR__.DIRECTORY_SEPARATOR.'../../../../_files/expected-fieldsets/inline-fieldset.html',
			$this->formCollectionHelper->__invoke($oForm->get('inline-fieldset'),true,'inline')
		);
    }
}