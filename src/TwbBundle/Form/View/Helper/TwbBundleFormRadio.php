<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleFormRadio extends \Zend\Form\View\Helper\FormRadio{
	/**
	 * @var string
	 */
	private static $checkboxFormat = '<div class="radio">%s</div>';

	/**
	 * @see \Zend\Form\View\Helper\FormRadio::render()
	 * @param \Zend\Form\ElementInterface $oElement
	 * @return string
	 */
	public function render(\Zend\Form\ElementInterface $oElement){
		return sprintf(self::$checkboxFormat,parent::render($oElement));
	}
}