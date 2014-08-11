<?php

namespace TwbBundle\Form\View\Helper;

class TwbBundleFormRow extends \Zend\Form\View\Helper\FormRow {

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
     * @see \Zend\Form\View\Helper\FormRow::render()
     * @param \Zend\Form\ElementInterface $oElement
     * @return string
     */
    public function render(\Zend\Form\ElementInterface $oElement) {
        $sElementType = $oElement->getAttribute('type');

        //Nothing to do for hidden elements which have no messages
        if ($sElementType === 'hidden' && !$oElement->getMessages()) {
            return parent::render($oElement);
        }

        //Retrieve expected layout
        $sLayout = $oElement->getOption('twb-layout');

        //Partial rendering
        if ($this->partial) {
            return $this->view->render($this->partial, array(
                        'element' => $oElement,
                        'label' => $this->renderLabel($oElement),
                        'labelAttributes' => $this->labelAttributes,
                        'labelPosition' => $this->labelPosition,
                        'renderErrors' => $this->renderErrors,
            ));
        }

        $sRowClass = '';

        //Validation state
        if (($sValidationState = $oElement->getOption('validation-state'))) {
            $sRowClass .= ' has-' . $sValidationState;
        }

        //"has-error" validation state case
        if ($oElement->getMessages()) {
            $sRowClass .= ' has-error';
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

        //Column size
        if (
                ($sColumSize = $oElement->getOption('column-size')) && $sLayout !== \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL
        ) {
            $sRowClass .= ' col-' . $sColumSize;
        }

        //Render element
        $sElementContent = $this->renderElement($oElement);

        //Render form row
        if ($sElementType === 'checkbox' && $sLayout !== \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL) {
            return $sElementContent . PHP_EOL;
        }
        if (($sElementType === 'submit' || $sElementType === 'button' || $sElementType === 'reset') && $sLayout === \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE
        ) {
            return $sElementContent . PHP_EOL;
        }

        return sprintf(self::$formGroupFormat, $sRowClass, $sElementContent) . PHP_EOL;
    }

    /**
     * Render element's label
     * @param \Zend\Form\ElementInterface $oElement
     * @return string
     */
    protected function renderLabel(\Zend\Form\ElementInterface $oElement) {
        if (($sLabel = $oElement->getLabel()) && ($oTranslator = $this->getTranslator())) {
            $sLabel = $oTranslator->translate($sLabel, $this->getTranslatorTextDomain());
        }
        return $sLabel;
    }

    /**
     * Render element
     * @param \Zend\Form\ElementInterface $oElement
     * @throws \DomainException
     * @return string
     */
    protected function renderElement(\Zend\Form\ElementInterface $oElement) {
        //Retrieve expected layout
        $sLayout = $oElement->getOption('twb-layout');

        //Render label
        $sLabelOpen = $sLabelClose = $sLabelContent = $sElementType = '';
        if ($sLabelContent = $this->renderLabel($oElement)) {
            //Multicheckbox elements have to be handled differently as the HTML standard does not allow nested labels. The semantic way is to group them inside a fieldset
            $sElementType = $oElement->getAttribute('type');

            //Button element is a special case, because label is always rendered inside it
            if ($oElement instanceof \Zend\Form\Element\Button) {
                $sLabelContent = '';
            } else {
                $aLabelAttributes = $oElement->getLabelAttributes() ? : $this->labelAttributes;

                //Validation state
                if ($oElement->getOption('validation-state') || $oElement->getMessages()) {
                    if (empty($aLabelAttributes['class'])) {
                        $aLabelAttributes['class'] = 'control-label';
                    } elseif (!preg_match('/(\s|^)control-label(\s|$)/', $aLabelAttributes['class'])) {
                        $aLabelAttributes['class'] = trim($aLabelAttributes['class'] . ' control-label');
                    }
                }

                $oLabelHelper = $this->getLabelHelper();
                switch ($sLayout) {
                    //Hide label for "inline" layout
                    case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE:
                        if ($sElementType !== 'checkbox') {
                            if (empty($aLabelAttributes['class'])) {
                                $aLabelAttributes['class'] = 'sr-only';
                            } elseif (!preg_match('/(\s|^)sr-only(\s|$)/', $aLabelAttributes['class'])) {
                                $aLabelAttributes['class'] = trim($aLabelAttributes['class'] . ' sr-only');
                            }
                        }
                        break;

                    case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL:
                        if (empty($aLabelAttributes['class'])) {
                            $aLabelAttributes['class'] = 'control-label';
                        } else {
                            if (!preg_match('/(\s|^)control-label(\s|$)/', $aLabelAttributes['class'])) {
                                $aLabelAttributes['class'] = trim($aLabelAttributes['class'] . ' control-label');
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

                if (!$oElement instanceof \Zend\Form\LabelAwareInterface || !$oElement->getLabelOption('disable_html_escape')) {
                    $sLabelContent = $this->getEscapeHtmlHelper()->__invoke($sLabelContent);
                }
            }
        }

        //Add required string if element is required
        if ($this->requiredFormat && $oElement->getAttribute('required') && strpos($this->requiredFormat, $sLabelContent) === false) {
            $sLabelContent .= $this->requiredFormat;
        }

        switch ($sLayout) {
            case null:
            case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE:

                $sElementContent = $this->getElementHelper()->render($oElement);

                // Checkbox elements are a special case, element is already rendered into label
                if ($sElementType === 'checkbox') {
                    $sElementContent = sprintf(self::$checkboxFormat, $sElementContent);
                } else {
                    if ($this->getLabelPosition() === self::LABEL_PREPEND) {
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

            case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL:
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
                            self::$horizontalLayoutFormat, $sClass, sprintf(self::$checkboxFormat, $sElementContent)
                    );
                }

                if ($this->getLabelPosition() === self::LABEL_PREPEND) {
                    return $sLabelOpen . $sLabelContent . $sLabelClose . sprintf(
                                    self::$horizontalLayoutFormat, $sClass, $sElementContent
                    );
                } else {
                    return sprintf(
                                    self::$horizontalLayoutFormat, $sClass, $sElementContent
                            ) . $sLabelOpen . $sLabelContent . $sLabelClose;
                }

            default:
                throw new \DomainException('Layout "' . $sLayout . '" is not valid');
        }
    }

    /**
     * Render element's help block
     * @param \Zend\Form\ElementInterface $oElement
     * @return string
     */
    protected function renderHelpBlock(\Zend\Form\ElementInterface $oElement) {
        return ($sHelpBlock = $oElement->getOption('help-block')) ? sprintf(
                        self::$helpBlockFormat, $this->getEscapeHtmlHelper()->__invoke(($oTranslator = $this->getTranslator()) ? $oTranslator->translate($sHelpBlock, $this->getTranslatorTextDomain()) : $sHelpBlock)
                ) : '';
    }

}
