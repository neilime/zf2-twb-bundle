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
		$match = isset($oElementOptions['inline']) && $oElementOptions['inline'] == false ? 'checkbox' : 'checkbox-inline'; 
			 
		if (empty($aLabelAttributes['class'])) $aLabelAttributes['class'] = $match;
		elseif (!preg_match('/(\s|^)'.$match.'(\s|$)/',$aLabelAttributes['class'])) $aLabelAttributes['class'] .= ' '.$match;
		$oElement->setLabelAttributes($aLabelAttributes);
		
		return parent::render($oElement);
	}
}