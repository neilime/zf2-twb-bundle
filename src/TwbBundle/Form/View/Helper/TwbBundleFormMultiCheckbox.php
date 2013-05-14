<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleFormMultiCheckbox extends \Zend\Form\View\Helper\FormMultiCheckbox{
	/**
	 * Render a form <input> element from the provided $element
	 *
	 * @param  ElementInterface $element
	 * @throws Exception\InvalidArgumentException
	 * @throws Exception\DomainException
	 * @return string
	 */
	public function render(\Zend\Form\ElementInterface $oElement){
		if(!($oElement instanceof \Zend\Form\Element\MultiCheckbox))throw new Exception\InvalidArgumentException(sprintf(
			'%s requires that the element is of type Zend\Form\Element\MultiCheckbox',
			__METHOD__
		));

		$aOptions = $oElement->getValueOptions();

		if(empty($aOptions))throw new Exception\DomainException(sprintf(
			'%s requires that the element has "value_options"; none found',
			__METHOD__
		));

		foreach($aOptions as $aOption){

		}

		return parent::render($oElement);
	}
}