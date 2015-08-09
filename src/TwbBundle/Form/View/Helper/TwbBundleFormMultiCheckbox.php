<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleFormMultiCheckbox extends \Zend\Form\View\Helper\FormMultiCheckbox{
	/**
	 * @see \Zend\Form\View\Helper\FormMultiCheckbox::render()
	 * @param \Zend\Form\ElementInterface $oElement
	 * @return string
	 */
	public function render(\Zend\Form\ElementInterface $oElement){

		$aElementOptions  = $oElement->getOptions();
		$sCheckboxClass = isset($aElementOptions['inline']) && $aElementOptions['inline'] == false?'checkbox':'checkbox-inline';

	    $aLabelAttributes = $oElement->getLabelAttributes();
		if(empty($aLabelAttributes['class']))$aLabelAttributes['class'] = $sCheckboxClass;
		elseif(!preg_match('/(\s|^)'.$sCheckboxClass.'(\s|$)/',$aLabelAttributes['class']))$aLabelAttributes['class'] .= ' '.$sCheckboxClass;
		$oElement->setLabelAttributes($aLabelAttributes);

		return parent::render($oElement);
	}
}
