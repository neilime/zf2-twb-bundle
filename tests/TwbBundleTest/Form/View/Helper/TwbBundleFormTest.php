<?php
namespace TwbBundleTest\Form\View\Helper;

use TwbBundleTest\Bootstrap;
use Zend\Form\Form;
use Zend\View\Renderer\PhpRenderer;

class TwbBundleFormTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var \TwbBundle\Form\View\Helper\TwbBundleForm
     */
    protected $formHelper;

    /**
     * @see \PHPUnit_Framework_TestCase::setUp()
     */
    public function setUp()
    {
        $viewHelperManager = Bootstrap::getServiceManager()->get('view_helper_manager');
        $oRenderer         = new PhpRenderer();
        $oRenderer->setHelperPluginManager($viewHelperManager);

        $this->formHelper = $viewHelperManager->get('form')
                                              ->setView($oRenderer);
    }

    public function testInvoke()
    {
        $this->assertSame($this->formHelper, $this->formHelper->__invoke());
    }

    public function testRenderFormWithClassAlreadyDefined()
    {
        $oForm = new Form(null, ['attributes' => ['class' => 'test-class']]);
        $oForm->setAttribute('class', 'test-class');
        $this->formHelper->render($oForm);
        $this->assertEquals('test-class', $oForm->getAttribute('class'));
    }
}
