<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleFormStatic extends \Zend\Form\View\Helper\AbstractHelper{
	/**
	 * @var string
	 */
	private static $staticFormat = '<p class="form-control-static">%s</p>';
	
	/**
	 * @see \Zend\Form\View\Helper\AbstractHelper::render()
	 * @param \Zend\Form\ElementInterface $oElement
	 * @return string
	 */
	public function render(\Zend\Form\ElementInterface $oElement){
		return sprintf(self::$staticFormat,$oElement->getValue());
	}
}