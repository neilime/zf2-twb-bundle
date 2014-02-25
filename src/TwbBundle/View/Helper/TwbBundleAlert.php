<?php
namespace TwbBundle\View\Helper;
class TwbBundleAlert extends \Zend\Form\View\Helper\AbstractHelper{
	/**
	 * @var string
	 */
	private static $alertFormat = '<div %s>%s</div>';

	/**
	 * @var string
	 */
	private static $dismissButtonFormat = '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';

	/**
	 * Invoke helper as functor, proxies to {@link render()}.
	 * @param string $sAlertMessage
	 * @param string|array $aAlertAttributes : [optionnal] if string, alert class
	 * @param boolean $bDismissable
	 * @return string|\TwbBundle\View\Helper\TwbBundleAlert
	 */
	public function __invoke($sAlertMessage = null, $aAlertAttributes = null, $bDismissable = false){
		if($sAlertMessage === null) return $this;
		if($sAlertMessage === '' || $sAlertMessage === false) return '';
		return $this->render($sAlertMessage,$aAlertAttributes,$bDismissable);
	}

	/**
	 * Retrieve alert markup
	 * @param string $sAlertMessage
	 * @param  string|array $aAlertAttributes : [optionnal] if string, alert class
	 * @param boolean $bDismissable
	 * @throws \InvalidArgumentException
	 * @return string
	 */
	public function render($sAlertMessage, $aAlertAttributes = null, $bDismissable = false){
		if(!is_scalar($sAlertMessage))throw new \InvalidArgumentException('Alert message expects a scalar value, "'.gettype($sAlertMessage).'" given');
		if(empty($aAlertAttributes))$aAlertAttributes = array('class' => 'alert');
		elseif(is_string($aAlertAttributes))$aAlertAttributes = array('class' => $aAlertAttributes);
		elseif(!is_array($aAlertAttributes))throw new \InvalidArgumentException('Alert attributes expects a string or an array, "'.gettype($aAlertAttributes).'" given');
		elseif(empty($aAlertAttributes['class']))throw new \InvalidArgumentException('Alert "class" attribute is empty');
		elseif(!is_string($aAlertAttributes['class']))throw new \InvalidArgumentException('Alert "class" attribute expects string, "'.gettype($aAlertAttributes).'" given');

		if(!preg_match('/(\s|^)alert(\s|$)/',$aAlertAttributes['class']))$aAlertAttributes['class'] .= ' alert';
		if(null !== ($oTranslator = $this->getTranslator()))$sAlertMessage = $oTranslator->translate($sAlertMessage, $this->getTranslatorTextDomain());

		if($bDismissable){
			$sAlertMessage = self::$dismissButtonFormat . $sAlertMessage;
			if(!preg_match('/(\s|^)alert-dismissable(\s|$)/',$aAlertAttributes['class']))$aAlertAttributes['class'] .= ' alert-dismissable';
		}

		return sprintf(
			self::$alertFormat,
			$this->createAttributesString($aAlertAttributes),
			$sAlertMessage
		);
	}
}
