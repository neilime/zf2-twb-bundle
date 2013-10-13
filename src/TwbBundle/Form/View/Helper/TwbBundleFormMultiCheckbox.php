<?php
namespace TwbBundle\Form\View\Helper;

class TwbBundleFormMultiCheckbox extends \Zend\Form\View\Helper\FormMultiCheckbox
{
	/**
	 * @see \Zend\Form\View\Helper\FormRadio::render()
	 * @param \Zend\Form\ElementInterface $oElement
	 * @return string
	 */
	public function render(\Zend\Form\ElementInterface $oElement)
	{
		$aLabelAttributes = $oElement->getLabelAttributes();
		$oElementOptions  = $oElement->getOptions();
		if (isset($oElementOptions['inline']) && $oElementOptions['inline'] == true) {
			if (empty($aLabelAttributes['class'])) $aLabelAttributes['class'] = 'checkbox-inline';
			elseif (!preg_match('/(\s|^)checkbox-inline(\s|$)/',$aLabelAttributes['class'])) $aLabelAttributes['class'] .= ' checkbox-inline';
			$oElement->setLabelAttributes($aLabelAttributes);
		}
		return parent::render($oElement);
	}
}