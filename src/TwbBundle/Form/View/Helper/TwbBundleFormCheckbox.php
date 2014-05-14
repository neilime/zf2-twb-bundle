<?php

namespace TwbBundle\Form\View\Helper;

class TwbBundleFormCheckbox extends \Zend\Form\View\Helper\FormCheckbox {

    /**
     * Form label helper instance
     * @var \Zend\Form\View\Helper\FormLabel
     */
    protected $labelHelper;

    /**
     * @see \Zend\Form\View\Helper\FormCheckbox::render()
     * @param \Zend\Form\ElementInterface $oElement
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @return string
     */
    public function render(\Zend\Form\ElementInterface $oElement) {
        if ($oElement->getOption('disable-twb')) {
            return parent::render($oElement);
        }

        if (!$oElement instanceof \Zend\Form\Element\Checkbox) {
            throw new \InvalidArgumentException(sprintf(
                    '%s requires that the element is of type Zend\Form\Element\Checkbox', __METHOD__
            ));
        }
        if (($sName = $oElement->getName()) !== 0 && empty($sName)) {
            throw new \LogicException(sprintf(
                    '%s requires that the element has an assigned name; none discovered', __METHOD__
            ));
        }

        $aAttributes = $oElement->getAttributes();
        $aAttributes['name'] = $sName;
        $aAttributes['type'] = $this->getInputType();
        $aAttributes['value'] = $oElement->getCheckedValue();
        $sClosingBracket = $this->getInlineClosingBracket();

        if ($oElement->isChecked()) {
            $aAttributes['checked'] = 'checked';
        }

        //Render label
        $sLabelOpen = $sLabelClose = $sLabelContent = '';

        //Render label and visible element
        if ($sLabelContent = $oElement->getLabel()) {
            if ($oTranslator = $this->getTranslator()) {
                $sLabelContent = $oTranslator->translate($sLabelContent, $this->getTranslatorTextDomain());
            }


            $oLabelHelper = $this->getLabelHelper();
            $sLabelOpen = $oLabelHelper->openTag($oElement->getLabelAttributes() ? : null);
            $sLabelClose = $oLabelHelper->closeTag();
        }

        //Render checkbox
        $sElementContent = sprintf('<input %s%s', $this->createAttributesString($aAttributes), $sClosingBracket);

        //Add label markup
        if ($this->getLabelPosition($oElement) === \Zend\Form\View\Helper\FormRow::LABEL_PREPEND) {
            $sElementContent = $sLabelOpen . ($sLabelContent ? rtrim($sLabelContent) . ' ' : '') . $sElementContent . $sLabelClose;
        } else {
            $sElementContent = $sLabelOpen . $sElementContent . ($sLabelContent ? ' ' . ltrim($sLabelContent) : '') . $sLabelClose;
        }

        //Render hidden input
        if ($oElement->useHiddenElement()) {
            $sElementContent = sprintf(
                            '<input type="hidden" %s%s', $this->createAttributesString(array(
                                'name' => $aAttributes['name'],
                                'value' => $oElement->getUncheckedValue(),
                            )), $sClosingBracket
                    ) . $sElementContent;
        }
        return $sElementContent;
    }

    /**
     * Get the label position
     * @return string
     */
    public function getLabelPosition(\Zend\Form\Element\Checkbox $oElement) {
        return $oElement->getLabelOption('position')? : \Zend\Form\View\Helper\FormRow::LABEL_APPEND;
    }

    /**
     * Retrieve the FormLabel helper
     * @return \Zend\Form\View\Helper\FormLabel
     */
    protected function getLabelHelper() {
        if ($this->labelHelper) {
            return $this->labelHelper;
        }
        if (method_exists($this->view, 'plugin')) {
            $this->labelHelper = $this->view->plugin('form_label');
        }
        if (!($this->labelHelper instanceof \Zend\Form\View\Helper\FormLabel)) {
            $this->labelHelper = new \Zend\Form\View\Helper\FormLabel();
        }
        if ($this->hasTranslator()) {
            $this->labelHelper->setTranslator($this->getTranslator(), $this->getTranslatorTextDomain());
        }
        return $this->labelHelper;
    }

}
