<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleFormCheckbox extends \Zend\Form\View\Helper\FormCheckbox{
	/**
	 * @var string
	 */
	private static $checkboxFormat = '<div class="checkbox">%s</div>';

	/**
	 * Form label helper instance
	 * @var \Zend\Form\View\Helper\FormLabel
	 */
	protected $labelHelper;

	/**
	 * @see \Zend\Form\View\Helper\FormCheckbox::render()
	 * @param \Zend\Form\ElementInterface $oElement
	 * @return string
	 */
	public function render(\Zend\Form\ElementInterface $oElement){
		if(!$oElement instanceof \Zend\Form\Element\Checkbox)throw new Exception\InvalidArgumentException(sprintf(
			'%s requires that the element is of type Zend\Form\Element\Checkbox',
			__METHOD__
		));
        if(($sName = $oElement->getName()) !== 0 && empty($sName))throw new Exception\DomainException(sprintf(
        	'%s requires that the element has an assigned name; none discovered',
        	__METHOD__
        ));

        $aAttributes = $oElement->getAttributes();
        $aAttributes['name'] = $sName;
        $aAttributes['type'] = $this->getInputType();
        $aAttributes['value'] = $oElement->getCheckedValue();
        $sClosingBracket = $this->getInlineClosingBracket();

        if($oElement->isChecked())$aAttributes['checked'] = 'checked';

        //Render label and visible element
        if(($sLabel = $oElement->getLabel()) && ($oTranslator = $this->getTranslator()))$sLabel = $oTranslator->translate($sLabel,$this->getTranslatorTextDomain());
        $oLabelHelper = $this->getLabelHelper();
        $sElementContent = $oLabelHelper->openTag($oElement->getLabelAttributes()?:null).sprintf(
            '<input %s%s',
            $this->createAttributesString($aAttributes),
            $sClosingBracket
        ).'&nbsp;'.$sLabel.$oLabelHelper->closeTag();

        //Render hidden input
        if($oElement->useHiddenElement())$sElementContent = sprintf(
        	'<input type="hidden" %s%s',
        	$this->createAttributesString(array(
        		'name'  => $aAttributes['name'],
        		'value' => $oElement->getUncheckedValue(),
        	)),$sClosingBracket
        ).$sElementContent;

        return sprintf(self::$checkboxFormat,$sElementContent);
	}


	/**
	 * Retrieve the FormLabel helper
	 * @return \Zend\Form\View\Helper\FormLabel
	 */
	protected function getLabelHelper(){
		if($this->labelHelper)return $this->labelHelper;
		if(method_exists($this->view, 'plugin'))$this->labelHelper = $this->view->plugin('form_label');
		if(!$this->labelHelper instanceof FormLabel)$this->labelHelper = new \Zend\Form\View\Helper\FormLabel();
		if($this->hasTranslator()) $this->labelHelper->setTranslator($this->getTranslator(),$this->getTranslatorTextDomain());
		return $this->labelHelper;
	}

}