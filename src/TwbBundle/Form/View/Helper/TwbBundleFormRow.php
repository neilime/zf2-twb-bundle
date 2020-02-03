<?php

namespace TwbBundle\Form\View\Helper;

use DomainException;
use Laminas\Form\View\Helper\FormRow;
use Laminas\Form\ElementInterface;
use Laminas\Form\LabelAwareInterface;
use Laminas\Form\Element\Button;
use Laminas\Form\Element\Submit;

class TwbBundleFormRow extends FormRow
{

    /**
     * @var string
     */
    protected static $formGroupFormat = '<div class="form-group %s">%s</div>';

    /**
     * @var string
     */
    protected static $horizontalLayoutFormat = '<div class="%s">%s</div>';

    /**
     * @var string
     */
    protected static $checkboxFormat = '<div class="checkbox">%s</div>';

    /**
     * @var string
     */
    protected static $helpBlockFormat = '<p class="help-block">%s</p>';

    /**
     * The class that is added to element that have errors
     * @var string
     */
    protected $inputErrorClass = '';

    /**
     * @var string
     */
    protected $requiredFormat = null;

    /**
     * @see FormRow::render()
     * @param ElementInterface $oElement
     * @return string
     */
    public function render(ElementInterface $oElement, $sLabelPosition = null)
    {
        // Retrieve element type
        $sElementType = $oElement->getAttribute('type');

        // Nothing to do for hidden elements which have no messages
        if ($sElementType === 'hidden' && !$oElement->getMessages()) {
            return parent::render($oElement, $sLabelPosition);
        }

        // Retrieve expected layout
        $sLayout = $oElement->getOption('twb-layout');

        // Define label position
        if ($sLabelPosition === null) {
            $sLabelPosition = $this->getLabelPosition();
        }

        // Partial rendering
        if ($this->partial) {
            return $this->view->render($this->partial, array(
                        'element' => $oElement,
                        'label' => $this->renderLabel($oElement),
                        'labelAttributes' => $this->labelAttributes,
                        'labelPosition' => $sLabelPosition,
                        'renderErrors' => $this->renderErrors,
            ));
        }

        // "has-error" validation state case
        if ($oElement->getMessages()) {
            //Element have errors
            if ($sInputErrorClass = $this->getInputErrorClass()) {
                if ($sElementClass = $oElement->getAttribute('class')) {
                    if (!preg_match('/(\s|^)' . preg_quote($sInputErrorClass, '/') . '(\s|$)/', $sElementClass)) {
                        $oElement->setAttribute('class', trim($sElementClass . ' ' . $sInputErrorClass));
                    }
                } else {
                    $oElement->setAttribute('class', $sInputErrorClass);
                }
            }
        }

        // Render element
        $sElementContent = $this->renderElement($oElement, $sLabelPosition);

        // Render form row
        switch (true) {
            // Checkbox element not in horizontal form
            case $sElementType === 'checkbox' && $sLayout !== TwbBundleForm::LAYOUT_HORIZONTAL && !$oElement->getOption('form-group'):
            // All "button" elements in inline form
            case in_array($sElementType, array('submit', 'button', 'reset'), true) && $sLayout === TwbBundleForm::LAYOUT_INLINE:
                return $sElementContent . "\n";
            default:
                // Render element into form group
                return $this->renderElementFormGroup($sElementContent, $this->getRowClassFromElement($oElement), $oElement->getOption('feedback'));
        }
    }

    /**
     * @param ElementInterface $oElement
     * @return string
     */
    public function getRowClassFromElement(\Laminas\Form\ElementInterface $oElement)
    {
        $sRowClass = '';
        if ($sFormGroupSize = $oElement->getOption('twb-form-group-size')) {
            $sRowClass = $sFormGroupSize;
        }

        // Validation state
        if (($sValidationState = $oElement->getOption('validation-state'))) {
            $sRowClass .= ' has-' . $sValidationState;
        }
        if ($oElement->getMessages()) {
            $sRowClass .= ' has-error';
        }
        if( $oElement->getOption('feedback')) {
            $sRowClass .= ' has-feedback';
        }

        // Column size
        if (($sColumSize = $oElement->getOption('column-size')) && $oElement->getOption('twb-layout') !== TwbBundleForm::LAYOUT_HORIZONTAL
        ) {
            $sColumSize = (is_array($sColumSize)) ? $sColumSize : array($sColumSize);
            $sRowClass .= implode('', array_map(function($item) { return ' col-' . $item; }, $sColumSize));
        }

        //Additional row class
        if ($sAddRowClass = $oElement->getOption('twb-row-class')) {
            $sRowClass .= ' ' . $sAddRowClass;
        }
        return $sRowClass;
    }

    /**
     * @param string $sElementContent
     * @param string $sRowClass
     * @param string $sFeedbackElement A feedback element that should be rendered within the element, optional
     *
     * @return string
     * @throws \InvalidArgumentException
     */
    public function renderElementFormGroup($sElementContent, $sRowClass, $sFeedbackElement = '' )
    {
        if (!is_string($sElementContent)) {
            throw new \InvalidArgumentException('Argument "$sElementContent" expects a string, "' . (is_object($sElementContent) ? get_class($sElementContent) : gettype($sElementContent)) . '" given');
        }
        if (!is_string($sRowClass)) {
            throw new \InvalidArgumentException('Argument "$sRowClass" expects a string, "' . (is_object($sRowClass) ? get_class($sRowClass) : gettype($sRowClass)) . '" given');
        }
        if ($sFeedbackElement && !is_string($sFeedbackElement)) {
            throw new \InvalidArgumentException('Argument "$sFeedbackElement" expects a string, "' . (is_object($sFeedbackElement) ? get_class($sFeedbackElement) : gettype($sFeedbackElement)) . '" given');
        }
        if( $sFeedbackElement ){
            $sElementContent .= '<i class="' . $sFeedbackElement . ' form-control-feedback"></i>';
        }
        return sprintf(static::$formGroupFormat, $sRowClass, $sElementContent) . "\n";
    }

    /**
     * Render element's label
     * @param ElementInterface $oElement
     * @return string
     */
    protected function renderLabel(ElementInterface $oElement)
    {
        if (($sLabel = $oElement->getLabel()) && ($oTranslator = $this->getTranslator())) {
            $sLabel = $oTranslator->translate($sLabel, $this->getTranslatorTextDomain());
        }
        return $sLabel;
    }

    /**
     * Render element
     * @param ElementInterface $oElement
     * @param string $sLabelPosition
     * @return type
     * @throws DomainException
     */
    protected function renderElement(ElementInterface $oElement, $sLabelPosition = null)
    {
        //Retrieve expected layout
        $sLayout = $oElement->getOption('twb-layout');

        // Define label position
        if ($sLabelPosition === null) {
            $sLabelPosition = $this->getLabelPosition();
        }

        //Render label
        $sLabelOpen = $sLabelClose = $sLabelContent = $sElementType = '';
        if ($sLabelContent = $this->renderLabel($oElement)) {
            /*
             * Multicheckbox elements have to be handled differently
             * as the HTML standard does not allow nested labels.
             * The semantic way is to group them inside a fieldset
             */
            $sElementType = $oElement->getAttribute('type');

            //Button element is a special case, because label is always rendered inside it
            if (($oElement instanceof Button) or ( $oElement instanceof Submit)) {
                $sLabelContent = '';
            } else {
                $aLabelAttributes = $oElement->getLabelAttributes() ? : $this->labelAttributes;

                //Validation state
                if ($oElement->getOption('validation-state') || $oElement->getMessages()) {
                    if (empty($aLabelAttributes['class'])) {
                        $aLabelAttributes['class'] = 'control-label';
                    } elseif (!preg_match('/(\s|^)control-label(\s|$)/', $aLabelAttributes['class']) && $sElementType !== 'checkbox') {
                        $aLabelAttributes['class'] = trim($aLabelAttributes['class'] . ' control-label');
                    }
                }

                $oLabelHelper = $this->getLabelHelper();
                switch ($sLayout) {
                    //Hide label for "inline" layout
                    case TwbBundleForm::LAYOUT_INLINE:
                        if ($sElementType !== 'checkbox') {
                            if ($sElementType !== 'checkbox') {
                                if (empty($aLabelAttributes['class']) && empty($oElement->getOption('showLabel'))) {
                                    $aLabelAttributes['class'] = 'sr-only';
                                } elseif (empty($oElement->getOption('showLabel')) && !preg_match('/(\s|^)sr-only(\s|$)/', $aLabelAttributes['class'])) {
                                    $aLabelAttributes['class'] = trim($aLabelAttributes['class'] . ' sr-only');
                                }
                            }
                        }
                        break;

                    case TwbBundleForm::LAYOUT_HORIZONTAL:
                        if ($sElementType !== 'checkbox') {
                            if (empty($aLabelAttributes['class'])) {
                                $aLabelAttributes['class'] = 'control-label';
                            } else {
                                if (!preg_match('/(\s|^)control-label(\s|$)/', $aLabelAttributes['class'])) {
                                    $aLabelAttributes['class'] = trim($aLabelAttributes['class'] . ' control-label');
                                }
                            }
                        }
                        break;
                }
                if ($aLabelAttributes) {
                    $oElement->setLabelAttributes($aLabelAttributes);
                }

                $sLabelOpen = $oLabelHelper->openTag($oElement->getAttribute('id') ? $oElement : $aLabelAttributes);
                $sLabelClose = $oLabelHelper->closeTag();

                // Allow label html escape desable
                //$sLabelContent = $this->getEscapeHtmlHelper()->__invoke($sLabelContent);

                if (!$oElement instanceof LabelAwareInterface || !$oElement->getLabelOption('disable_html_escape')) {
                    $sLabelContent = $this->getEscapeHtmlHelper()->__invoke($sLabelContent);
                }
            }
        }

        //Add required string if element is required
        if ($this->requiredFormat &&
                $oElement->getAttribute('required') &&
                strpos($this->requiredFormat, $sLabelContent) === false
        ) {
            $sLabelContent .= $this->requiredFormat;
        }

        switch ($sLayout) {
            case null:
            case TwbBundleForm::LAYOUT_INLINE:

                $sElementContent = $this->getElementHelper()->render($oElement);

                // Checkbox elements are a special case, element is already rendered into label
                if ($sElementType === 'checkbox') {
                    $sElementContent = sprintf(static::$checkboxFormat, $sElementContent);
                } else {
                    if ($sLabelPosition === self::LABEL_PREPEND) {
                        $sElementContent = $sLabelOpen . $sLabelContent . $sLabelClose . $sElementContent;
                    } else {
                        $sElementContent = $sElementContent . $sLabelOpen . $sLabelContent . $sLabelClose;
                    }
                }

                //Render help block
                $sElementContent .= $this->renderHelpBlock($oElement);

                //Render errors
                if ($this->renderErrors) {
                    $sElementContent .= $this->getElementErrorsHelper()->render($oElement);
                }

                return $sElementContent;

            case TwbBundleForm::LAYOUT_HORIZONTAL:
                $sElementContent = $this->getElementHelper()->render($oElement) . $this->renderHelpBlock($oElement);

                //Render errors
                if ($this->renderErrors) {
                    $sElementContent .= $this->getElementErrorsHelper()->render($oElement);
                }

                $sClass = '';

                //Column size
                if ($sColumSize = $oElement->getOption('column-size')) {
                    $sClass .= ' col-' . $sColumSize;
                }

                // Checkbox elements are a special case, element is rendered into label
                if ($sElementType === 'checkbox') {
                    return sprintf(
                            static::$horizontalLayoutFormat, $sClass, sprintf(static::$checkboxFormat, $sElementContent)
                    );
                }

                if ($sLabelPosition === self::LABEL_PREPEND) {
                    return $sLabelOpen . $sLabelContent . $sLabelClose . sprintf(
                                    static::$horizontalLayoutFormat, $sClass, $sElementContent
                    );
                } else {
                    return sprintf(
                                    static::$horizontalLayoutFormat, $sClass, $sElementContent
                            ) . $sLabelOpen . $sLabelContent . $sLabelClose;
                }
        }
        throw new DomainException('Layout "' . $sLayout . '" is not valid');
    }

    /**
     * Render element's help block
     * @param ElementInterface $oElement
     * @return string
     */
    protected function renderHelpBlock(ElementInterface $oElement)
    {
        if ($sHelpBlock = $oElement->getOption('help-block')) {
            if ($oTranslator = $this->getTranslator()) {
                $sHelpBlock = $oTranslator->translate($sHelpBlock, $this->getTranslatorTextDomain());
            }
            $sHelpBlockString = strip_tags($sHelpBlock);
            if ($sHelpBlock === $sHelpBlockString) {
                $sHelpBlock = $this->getEscapeHtmlHelper()->__invoke($sHelpBlock);
            }
            return sprintf(static::$helpBlockFormat, $sHelpBlock);
        } else {
            return '';
        }
    }
}
