<?php

namespace TwbBundle\Form\View\Helper;

class TwbBundleFormMultiCheckbox extends \Zend\Form\View\Helper\FormMultiCheckbox {

    /**
     * @see \Zend\Form\View\Helper\FormMultiCheckbox::render()
     * @param \Zend\Form\ElementInterface $oElement
     * @return string
     */
    public function render(\Zend\Form\ElementInterface $oElement) {

        $aElementOptions = $oElement->getOptions();

        // For no inline multi-checkbox
        if ($bNoInline = (isset($aElementOptions['inline']) && $aElementOptions['inline'] == false)) {
            $sCheckboxClass = 'checkbox';
            $this->setSeparator('</div><div class="checkbox">');
        } else {
            $sCheckboxClass = 'checkbox-inline';
            $this->setSeparator('');
        }

        $oElement->setLabelAttributes(array('class' => $sCheckboxClass));

        return $bNoInline ? '<div class="checkbox">' . parent::render($oElement) . '</div>' : parent::render($oElement);
    }

}
