<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleFormRow extends \Zend\Form\View\Helper\FormRow{

	/**
	 * @var string
	 */
	private static $formGroupFormat = '<div class="form-group %s">%s</div>';

	/**
	 * @var string
	 */
	private static $horizontalLayoutFormat = '%s<div class="%s">%s</div>';

	/**
	 * @var string
	 */
	private static $helpBlockFormat = '<p class="help-block">%s</p>';

	/**
	 * @see \Zend\Form\View\Helper\FormRow::render()
	 * @param \Zend\Form\ElementInterface $oElement
	 * @return string
	 */
	public function render(\Zend\Form\ElementInterface $oElement){


		//Retrieve expected layout
		$sLayout = $oElement->getOption('twb-layout');

		//Partial rendering
		if($this->partial)return $this->view->render($this->partial, array(
			'element' => $oElement,
			'label' => $this->renderLabel($oElement),
			'labelAttributes' => $this->labelAttributes,
			'labelPosition' => $this->labelPosition,
			'renderErrors' => $this->renderErrors,
		));

		$sRowClass = '';

		//Validation state
		if(($sValidationState = $oElement->getOption('validation-state')))$sRowClass .= ' has-'.$sValidationState;

                //"has-error" validation state case
                if(count($oElement->getMessages()))$sRowClass .= ' has-error';
           
                
		//Column size
		if($iColumSize = $oElement->getOption('colunm-size'))$sRowClass .= 'col-lg-'.$iColumSize;

		//Render element
		$sElementContent = $this->renderElement($oElement).
		//Render errors
		$this->renderErrors($oElement).
		//Render help block
		if($sLayout !== \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL) {
			$this->renderHelpBlock($oElement);
		}

		//Render form row
		$sElementType = $oElement->getAttribute('type');
		if(in_array($sElementType,array('checkbox')) && $sLayout !== \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL)return $sElementContent.PHP_EOL;
		if($sElementType === 'submit' && $sLayout === \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE)return $sElementContent.PHP_EOL;

		return sprintf(
			self::$formGroupFormat,
			$sRowClass,
			$sElementContent
		).PHP_EOL;
	}

	/**
	 * Render element's label
	 * @param \Zend\Form\ElementInterface $oElement
	 * @return string
	 */
	protected function renderLabel(\Zend\Form\ElementInterface $oElement){
		if(($sLabel = $oElement->getLabel()) && ($oTranslator = $this->getTranslator()))$sLabel = $oTranslator->translate($sLabel,$this->getTranslatorTextDomain());
		return $sLabel;
	}

	/**
	 * Render element
	 * @param \Zend\Form\ElementInterface $oElement
	 * @return string
	 */
	protected function renderElement(\Zend\Form\ElementInterface $oElement){
		//Retrieve expected layout
		$sLayout = $oElement->getOption('twb-layout');

		//Render label
		if($sLabelContent = $this->renderLabel($oElement)){
			//Multicheckbox elements have to be handled differently as the HTML standard does not allow nested labels. The semantic way is to group them inside a fieldset
			$sElementType = $oElement->getAttribute('type');

			//Checkbox & radio elements are a special case, because label is rendered by their own helper
			if(in_array($sElementType,array('multi_checkbox','checkbox','radio'))){
				if(!$oElement->getLabelAttributes() && $this->labelAttributes)$oElement->setLabelAttributes($this->labelAttributes);

				//Render element input
				if($sLayout !== \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL)return $this->getElementHelper()->render($oElement);
				$sLabelOpen = $sLabelClose = $sLabelContent = '';
			}

			//Button element is a special case, because label is always rendered inside it
			elseif($oElement instanceof \Zend\Form\Element\Button)$sLabelOpen = $sLabelClose = $sLabelContent = '';
			else{
				$aLabelAttributes = $oElement->getLabelAttributes()?:$this->labelAttributes;

				//Validation state
				if($oElement->getOption('validation-state') || count($oElement->getMessages())){
					if(empty($aLabelAttributes['class']))$aLabelAttributes['class'] = 'control-label';
					elseif(!preg_match('/(\s|^)control-label(\s|$)/',$aLabelAttributes['class']))$aLabelAttributes['class'] = trim($aLabelAttributes['class'].' control-label');
				}

				$oLabelHelper = $this->getLabelHelper();
				switch($sLayout){
					//Hide label for "inline" layout
					case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE:
						if(empty($aLabelAttributes['class']))$aLabelAttributes['class'] = 'sr-only';
						elseif(!preg_match('/(\s|^)sr-only(\s|$)/',$aLabelAttributes['class']))$aLabelAttributes['class'] = trim($aLabelAttributes['class'].' sr-only');
						break;

					case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL:
						if(empty($aLabelAttributes['class']))$aLabelAttributes['class'] = 'col-lg-2 control-label';
						else{
							if(!preg_match('/(\s|^)col-lg-2(\s|$)/',$aLabelAttributes['class']))$aLabelAttributes['class'] = trim($aLabelAttributes['class'].' col-lg-2');
							if(!preg_match('/(\s|^)control-label(\s|$)/',$aLabelAttributes['class']))$aLabelAttributes['class'] = trim($aLabelAttributes['class'].' control-label');
						}
						break;
				}
				if($aLabelAttributes)$oElement->setLabelAttributes($aLabelAttributes);
				$sLabelOpen = $oLabelHelper->openTag($oElement);
				$sLabelClose = $oLabelHelper->closeTag();
				$sLabelContent = $this->getEscapeHtmlHelper()->__invoke($sLabelContent);
			}

			switch($sLayout){
				case null:
					return $sLabelOpen.$sLabelContent.$sLabelClose.$this->getElementHelper()->render($oElement);
				case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE:
					return $sLabelOpen.$sLabelContent.$sLabelClose.$this->getElementHelper()->render($oElement);
				case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL:
					$sClass = 'col-lg-10';

					//Element without labels
					if(!$sLabelContent)$sClass .= ' col-lg-offset-2';

					return sprintf(
						self::$horizontalLayoutFormat,
						$sLabelOpen.$sLabelContent.$sLabelClose,
						$sClass,
						$this->getElementHelper()->render($oElement) . $this->renderHelpBlock($oElement)
					);
				default:
					throw new \DomainException('Layout "'.$sLayout.'" is not valid');
			}
		}

		//Render element input
		return $this->getElementHelper()->render($oElement);
	}

	/**
	 * Render errors
	 * @param \Zend\Form\ElementInterface $oElement
	 * @return string
	 */
	protected function renderErrors(\Zend\Form\ElementInterface $oElement){
		//Element have errors
		if(count($oElement->getMessages()) && ($sInputErrorClass = $this->getInputErrorClass())){
			if($sElementClass = $oElement->getAttribute('class')){
				if(!preg_match('/(\s|^)'.preg_quote($sInputErrorClass,'/').'(\s|$)/',$sElementClass))$oElement->setAttribute('class',trim($sElementClass.' '.$sInputErrorClass));
			}
			else $oElement->setAttribute('class',$sInputErrorClass);
		}
		return $this->renderErrors?$this->getElementErrorsHelper()->render($oElement):'';
	}

	/**
	 * Render element's help block
	 * @param \Zend\Form\ElementInterface $oElement
	 * @return string
	 */
	protected function renderHelpBlock(\Zend\Form\ElementInterface $oElement){
		return ($sHelpBlock = $oElement->getOption('help-block'))?sprintf(
			self::$helpBlockFormat,
			$this->getEscapeHtmlHelper()->__invoke(($oTranslator = $this->getTranslator()) ? $oTranslator->translate($sHelpBlock,$this->getTranslatorTextDomain()) : $sHelpBlock)
		):'';
	}
}
