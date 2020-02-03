<?php

namespace TwbBundle\Form\View\Helper;

use Laminas\Form\View\Helper\Form;
use Laminas\Form\FormInterface;
use Laminas\Form\FieldsetInterface;

class TwbBundleForm extends Form
{

    /**
     * @var string
     */
    const LAYOUT_HORIZONTAL = 'horizontal';

    /**
     * @var string
     */
    const LAYOUT_INLINE = 'inline';

    /**
     * @var string
     */
    protected static $formRowFormat = '<div class="row">%s</div>';

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

        return $this->openTag($oForm) . "\n" . $this->renderElements($oForm, $sFormLayout) . $this->closeTag();
    }

    /**
     * @param FormInterface $oForm
     * @param string|null $sFormLayout
     * @return string
     */
    protected function renderElements(FormInterface $oForm, $sFormLayout = self::LAYOUT_HORIZONTAL)
    {
        // Store button groups
        $aButtonGroups = array();

        // Store button groups column-size from buttons
        $aButtonGroupsColumnSize = array();

        // Store elements rendering
        $aElementsRendering = array();

        // Retrieve view helper plugin manager
        $oHelperPluginManager = $this->getView()->getHelperPluginManager();

        // Retrieve form row helper
        $oFormRowHelper = $oHelperPluginManager->get('formRow');

        // Retrieve form collection helper
        $oFormCollectionHelper = $oHelperPluginManager->get('formCollection');

        // Retrieve button group helper
        $oButtonGroupHelper = $oHelperPluginManager->get('buttonGroup');

        // Store column size option
        $bHasColumnSize = false;

        // Prepare options
        foreach ($oForm as $iKey => $oElement) {
            $aOptions = $oElement->getOptions();
            if (!$bHasColumnSize && !empty($aOptions['column-size'])) {
                $bHasColumnSize = true;
            }
            // Define layout option to form elements if not already defined
            if ($sFormLayout && empty($aOptions['twb-layout'])) {
                $oElement->setOption('twb-layout', $sFormLayout);
            }

            // Manage button group option
            if (array_key_exists('button-group', $aOptions)) {
                $sButtonGroupKey = $aOptions['button-group'];
                if (isset($aButtonGroups[$sButtonGroupKey])) {
                    $aButtonGroups[$sButtonGroupKey][] = $oElement;
                } else {
                    $aButtonGroups[$sButtonGroupKey] = array($oElement);
                    $aElementsRendering[$iKey] = $sButtonGroupKey;
                }
                if (!empty($aOptions['column-size']) && !isset($aButtonGroupsColumnSize[$sButtonGroupKey])) {
                    // Only the first occured column-size will be set, other are ignored.
                    $aButtonGroupsColumnSize[$sButtonGroupKey] = $aOptions['column-size'];
                }
            } elseif ($oElement instanceof FieldsetInterface) {
                $aElementsRendering[$iKey] = $oFormCollectionHelper->__invoke($oElement);
            } else {
                $aElementsRendering[$iKey] = $oFormRowHelper->__invoke($oElement);
            }
        }

        // Assemble elements rendering
        $sFormContent = '';
        foreach ($aElementsRendering as $sElementRendering) {
            // Check if element rendering is a button group key
            if (isset($aButtonGroups[$sElementRendering])) {
                $aButtons = $aButtonGroups[$sElementRendering];

                // Render button group content
                $options = (isset($aButtonGroupsColumnSize[$sElementRendering])) ? array('attributes' => array('class' => 'col-' . $aButtonGroupsColumnSize[$sElementRendering])) : null;
                $sFormContent .= $oFormRowHelper->renderElementFormGroup($oButtonGroupHelper($aButtons, $options), $oFormRowHelper->getRowClassFromElement(current($aButtons)));
            } else {
                $sFormContent .= $sElementRendering;
            }
        }

        if ($bHasColumnSize && $sFormLayout !== self::LAYOUT_HORIZONTAL) {
            $sFormContent = sprintf(static::$formRowFormat, $sFormContent);
        }
        return $sFormContent;
    }

    /**
     * Sets form layout class
     * @param FormInterface $oForm
     * @param string $sFormLayout
     * @return \TwbBundle\Form\View\Helper\TwbBundleForm
     */
    protected function setFormClass(FormInterface $oForm, $sFormLayout = self::LAYOUT_HORIZONTAL)
    {
        if (is_string($sFormLayout)) {
            $sLayoutClass = 'form-' . $sFormLayout;
            if ($sFormClass = $oForm->getAttribute('class')) {
                if (!preg_match('/(\s|^)' . preg_quote($sLayoutClass, '/') . '(\s|$)/', $sFormClass)) {
                    $oForm->setAttribute('class', trim($sFormClass . ' ' . $sLayoutClass));
                }
            } else {
                $oForm->setAttribute('class', $sLayoutClass);
            }
        }
        return $this;
    }

    /**
     * Generate an opening form tag
     * @param null|FormInterface $form
     * @return string
     */
    public function openTag(FormInterface $form = null)
    {
        $this->setFormClass($form, $this->formLayout);
        return parent::openTag($form);
    }
}
