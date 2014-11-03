<?php
namespace TwbBundle\Form\View\Helper;

use Zend\Form\View\Helper\Form;
use Zend\Form\FormInterface;
use Zend\Form\FieldsetInterface;

class TwbBundleForm extends Form
{
    const LAYOUT_HORIZONTAL = 'horizontal';
    const LAYOUT_INLINE = 'inline';

    /**
     * @var string
     */
    private static $formRowFormat = '<div class="row">%s</div>';

    /**
     * Form layout (see LAYOUT_* consts)
     *
     * @var string
     */
    protected $formLayout = null;

    /**
     * @see Form::__invoke()
     * @param FormInterface $oForm
     * @param string $sFormLayout
     * @return TwbBundleForm|string
     */
    public function __invoke(FormInterface $oForm = null, $sFormLayout = self::LAYOUT_HORIZONTAL)
    {
        if ($oForm) {
            return $this->render($oForm, $sFormLayout);
        }
        $this->formLayout = $sFormLayout;
        return $this;
    }

    /**
     * Render a form from the provided $oForm,
     * @see Form::render()
     * @param FormInterface $oForm
     * @param string $sFormLayout
     * @return string
     */
    public function render(FormInterface $oForm, $sFormLayout = self::LAYOUT_HORIZONTAL)
    {
        //Prepare form if needed
        if (method_exists($oForm, 'prepare')) {
            $oForm->prepare();
        }

        $this->setFormClass($oForm, $sFormLayout);

        //Set form role
        if (!$oForm->getAttribute('role')) {
            $oForm->setAttribute('role', 'form');
        }

        $bHasColumnSizes = false;
        $sFormContent = '';
        $oRenderer = $this->getView();
        foreach ($oForm as $oElement) {
            $aOptions = $oElement->getOptions();
            if (!$bHasColumnSizes && !empty($aOptions['column-size'])) {
                $bHasColumnSizes = true;
            }
            //Define layout option to form elements if not already defined
            if ($sFormLayout && empty($aOptions['twb-layout'])) {
                $aOptions['twb-layout'] = $sFormLayout;
                $oElement->setOptions($aOptions);
            }
            $sFormContent .= $oElement instanceof FieldsetInterface ? $oRenderer->formCollection($oElement) : $oRenderer->formRow($oElement);
        }
        if ($bHasColumnSizes && $sFormLayout !== self::LAYOUT_HORIZONTAL) {
            $sFormContent = sprintf(self::$formRowFormat, $sFormContent);
        }
        return $this->openTag($oForm) . $sFormContent . $this->closeTag();
    }

    /**
     * Sets form layout class
     *
     * @param FormInterface $oForm
     * @param string $sFormLayout
     * @return void
     */
    protected function setFormClass(FormInterface $oForm, $sFormLayout = self::LAYOUT_HORIZONTAL)
    {
        if (is_string($sFormLayout)) {
            $sLayoutClass = 'form-'.$sFormLayout;
            if ($sFormClass = $oForm->getAttribute('class')) {
                if (!preg_match('/(\s|^)' . preg_quote($sLayoutClass, '/') . '(\s|$)/', $sFormClass)) {
                    $oForm->setAttribute('class', trim($sFormClass . ' ' . $sLayoutClass));
                }
            } else {
                $oForm->setAttribute('class', $sLayoutClass);
            }
        }
    }

    /**
     * Generate an opening form tag
     *
     * @param  null|FormInterface $form
     * @return string
     */
    public function openTag(FormInterface $form = null)
    {
        $this->setFormClass($form, $this->formLayout);
        return parent::openTag($form);
    }
}
