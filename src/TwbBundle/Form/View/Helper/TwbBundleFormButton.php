<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleFormButton extends \Zend\Form\View\Helper\FormButton{
	/**
	 * Render a form <button> element from the provided $element, using content from $buttonContent or the element's "label" attribute
	 * @param  ElementInterface $oElement
	 * @param  null|string $sButtonContent
	 * @return string
	 */
	public function render(\Zend\Form\ElementInterface $oElement, $sButtonContent = null){
		if($sClass = $oElement->getAttribute('class')){
			if(strpos($sClass, 'btn') === false)$oElement->setAttribute('class',$sClass.' btn');
		}
		else $oElement->setAttribute('class','btn');
		return parent::render($oElement, $sButtonContent);
	}
}