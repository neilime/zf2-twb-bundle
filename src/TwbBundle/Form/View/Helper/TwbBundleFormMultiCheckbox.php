<?php

namespace TwbBundle\Form\View\Helper;

use Zend\Form\View\Helper\FormMultiCheckbox;
use Zend\Form\ElementInterface;

class TwbBundleFormMultiCheckbox extends FormMultiCheckbox
{
    /**
     * @see FormMultiCheckbox::render()
     * @param ElementInterface $oElement
     * @return string
     */
    public function render(ElementInterface $oElement)
    {
        $aElementOptions = $oElement->getOptions();

        // For inline multi-checkbox
        if (isset($aElementOptions['inline']) && $aElementOptions['inline'] == true) {
            $this->setSeparator('');
            $oElement->setLabelAttributes(array('class' => 'checkbox-inline'));

            return sprintf('%s', parent::render($oElement));
        }

        $this->setSeparator('</div><div class="checkbox">');
        $oElement->setLabelAttributes(array('class' => 'checkbox'));

        return sprintf('<div class="checkbox">%s</div>', parent::render($oElement));
    }
}
