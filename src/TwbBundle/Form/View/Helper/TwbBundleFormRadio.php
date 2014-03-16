<?php

namespace TwbBundle\Form\View\Helper;

class TwbBundleFormRadio extends \Zend\Form\View\Helper\FormRadio {

    /**
     * Separator for checkbox elements
     * @var string
     */
    protected $separator = '</div><div class="radio">';

    /**
     * @var string
     */
    private static $checkboxFormat = '<div class="radio">%s</div>';

    /**
     * @see \Zend\Form\View\Helper\FormRadio::render()
     * @param \Zend\Form\ElementInterface $oElement
     * @return string
     */
    public function render(\Zend\Form\ElementInterface $oElement) {
        if ($oElement->getOption('disable-twb')) {
            $sSeparator = $this->separator;
            $this->separator = '';
            $sReturn = parent::render($oElement);
            $this->separator = $sSeparator;
            return $sReturn;
        }
        return sprintf(self::$checkboxFormat, parent::render($oElement));
    }

    /**
     * @see \Zend\Form\View\Helper\FormMultiCheckbox::renderOptions()
     * @param \Zend\Form\Element\MultiCheckbox $oElement
     * @param array $aOptions
     * @param array $aSelectedOptions
     * @param array $aAttributes
     * @return string
     */
    protected function renderOptions(\Zend\Form\Element\MultiCheckbox $oElement, array $aOptions, array $aSelectedOptions, array $aAttributes) {
        $iIterator = 0;
        $aGlobalLabelAttributes = $oElement->getLabelAttributes()? : $this->labelAttributes;
        $sMarkup = '';
        $oLabelHelper = $this->getLabelHelper();
        foreach ($aOptions as $key => $aOptionspec) {
            if (is_scalar($aOptionspec)) {
                $aOptionspec = array('label' => $aOptionspec, 'value' => $key);
            }

            $iIterator++;
            if ($iIterator > 1 && array_key_exists('id', $aAttributes)) {
                unset($aAttributes['id']);
            }

            //Option attributes
            $aInputAttributes = $aAttributes;
            if (isset($aOptionspec['attributes'])) {
                $aInputAttributes = \Zend\Stdlib\ArrayUtils::merge($aInputAttributes, $aOptionspec['attributes']);
            }

            //Option value
            $aInputAttributes['value'] = isset($aOptionspec['value']) ? $aOptionspec['value'] : '';

            //Selected option
            if (in_array($aInputAttributes['value'], $aSelectedOptions)) {
                $aInputAttributes['checked'] = true;
            } elseif (isset($aOptionspec['selected'])) {
                $aInputAttributes['checked'] = !!$aOptionspec['selected'];
            } else {
                $aInputAttributes['checked'] = isset($aInputAttributes['selected']) && $aInputAttributes['type'] !== 'radio' && $aInputAttributes['selected'] != false;
            }

            //Disabled option
            if (isset($aOptionspec['disabled'])) {
                $aInputAttributes['disabled'] = !!$aOptionspec['disabled'];
            } else {
                $aInputAttributes['disabled'] = isset($aInputAttributes['disabled']) && $aInputAttributes['disabled'] != false;
            }

            //Render option
            $sOptionMarkup = sprintf('<input %s%s', $this->createAttributesString($aInputAttributes), $this->getInlineClosingBracket());

            //Option label
            $sLabel = isset($aOptionspec['label']) ? $aOptionspec['label'] : '';
            if ($sLabel) {
                $aLabelAttributes = $aGlobalLabelAttributes;
                if (isset($aOptionspec['label_attributes'])) {
                    $aLabelAttributes = isset($aLabelAttributes) ? array_merge($aLabelAttributes, $aOptionspec['label_attributes']) : $aOptionspec['label_attributes'];
                }
                if (null !== ($oTranslator = $this->getTranslator())) {
                    $sLabel = $oTranslator->translate($sLabel, $this->getTranslatorTextDomain());
                }
                switch ($this->getLabelPosition()) {
                    case self::LABEL_PREPEND:
                        $sOptionMarkup = sprintf($oLabelHelper->openTag($aLabelAttributes) . '%s%s' . $oLabelHelper->closeTag(), $this->getEscapeHtmlHelper()->__invoke($sLabel), $sOptionMarkup);
                        break;
                    case self::LABEL_APPEND:
                    default:
                        $sOptionMarkup = sprintf($oLabelHelper->openTag($aLabelAttributes) . '%s%s' . $oLabelHelper->closeTag(), $sOptionMarkup, $this->getEscapeHtmlHelper()->__invoke($sLabel));
                        break;
                }
            }
            $sMarkup .= ($sMarkup ? $this->getSeparator() : '') . $sOptionMarkup;
        }
        return $sMarkup;
    }

}
