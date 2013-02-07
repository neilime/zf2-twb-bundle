<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleFormRow extends \Zend\Form\View\Helper\FormRow{
	/**
	 * @var string
	 */
	protected $formLayout;

	/**
	 * @param \Zend\Form\ElementInterface $oElement
	 * @param string $sLabelPosition
	 * @param boolean $bRenderErrors
	 * @return string|\TwbBundle\Form\View\Helper\TwbBundleFormRow
	 */
	public function __invoke(\Zend\Form\ElementInterface $oElement = null, $sLabelPosition = null, $bRenderErrors = null,$sFormLayout = \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_VERTICAL){
		if($sFormLayout)$this->setFormLayout($sFormLayout);
		return parent::__invoke($oElement,$sLabelPosition,$bRenderErrors);
	}

	/**
	 * Utility form helper that renders a label (if it exists), an element and errors
	 * @param \Zend\Form\ElementInterface $oElement
	 * @return string
	 */
	public function render(\Zend\Form\ElementInterface $oElement){
		//Form properties
		$sType = $oElement->getAttribute('type');
		$sFormLayout = $this->getFormLayout();
		$bIsInlineForm = in_array($sFormLayout,array(
			\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE,
			\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_SEARCH
		));

		//Helpers
		$oElementHelper = $this->getElementHelper();
		$oElementErrorsHelper = $this->getElementErrorsHelper()
		->setMessageOpenFormat('<div%s>')
		->setMessageSeparatorString('<br/>')
		->setMessageCloseString('</div>');


		if($sType === 'hidden')$sMarkup = $oElementHelper->render($oElement).$oElementErrorsHelper->render($oElement, array('class' => 'alert alert-error'));
		else{
			$bElementInLabel = in_array($sType,array('checkbox','radio'));

			if($bIsInlineForm && !$bElementInLabel
			&& !$oElement->getAttribute('placeholder')
			&& ($sLabel = $oElement->getLabel()))$oElement->setAttribute('placeholder',$sLabel);

			//Process elements options
			if(in_array($sType,array('multicheckbox','radio'))){
				$aOptions = $oElement->getValueOptions();
				$oElement->setAttribute('value_options',array_map(function($sOption,$sKey) use($sType,$bIsInlineForm){
					if(is_string($sOption)){
						$sClass = $sType === 'radio'?'radio':'checkbox';
						if($bIsInlineForm)$sClass .= ' inline';
						$sOption = array(
							'label' => $sOption,
							'value' => $sKey,
							'label_attributes' => array(
								'class' => $sClass
							)
						);
					}
					elseif(is_array($sOption)){
						if(empty($sOption['label_attributes']['class'])){
							$sClass = $sType === 'radio'?'radio':'checkbox';
							if($bIsInlineForm)$sClass .= ' inline';
							$sOption['label_attributes']['class'] = $sClass;
						}
						else{
							$sClass = $sType === 'radio'?'radio':'checkbox';
							if(strpos($sOption['label_attributes']['class'], $sClass) === false)$sOption['label_attributes']['class'] .= ' '.$sClass;
							if($bIsInlineForm && strpos($sOption['label_attributes']['class'], 'inline'))$sOption['label_attributes']['class'] .= ' inline';
						}
					}
					return $sOption;
				},$aOptions,array_keys($aOptions)));
			}
			//Buttons
			elseif(in_array($sType,array('submit','button'))){
				if($sClass = $oElement->getAttribute('class')){
					if(strpos($sClass, 'btn') === false)$oElement->setAttribute('class',$sClass.' btn');
				}
				else $oElement->setAttribute('class','btn');
			}

			//Render according to layout
			switch($sFormLayout){
				case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_VERTICAL:
					$sMarkup = $this->renderLabel($oElement);
					if(!$bElementInLabel)$sMarkup .= $this->renderElement($oElement);
					$sMarkup .= $oElementErrorsHelper->render($oElement, array('class' => 'help-block'));
					break;
				case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE:
				case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_SEARCH:
					$sMarkup = ($bElementInLabel?$this->renderLabel($oElement):$this->renderElement($oElement)).$oElementErrorsHelper->render($oElement, array('class' => 'help-block'));
					break;
				case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL:
					$sMarkup = '<div class="control-group'.(count($oElement->getMessages())?' error':'').'">'.($bElementInLabel?'<div class="controls">'
						.$this->renderLabel($oElement)
						.$oElementErrorsHelper->render($oElement, array('class' => 'help-block')).
					'</div>':$this->renderLabel($oElement).'<div class="controls">'
					.$this->renderElement($oElement).$oElementErrorsHelper->render($oElement, array('class' => 'help-block')).
					'</div>').'</div>';
					break;
			}
		}
		return $sMarkup.PHP_EOL;
	}

	/**
	 * Retrieve label markup
	 * @param \Zend\Form\ElementInterface $oElement
	 * @return string
	 */
	protected function renderLabel(\Zend\Form\ElementInterface $oElement){
		$sType = $oElement->getAttribute('type');
		if(in_array($sType,array('button','submit')))return '';

		$slabelOpen = $sLabelClose = '';
		if($sLabel = $oElement->getLabel()){
			$sLabel = $this->getEscapeHtmlHelper()->__invoke($sLabel);
			$aLabelAttributes = $oElement->getLabelAttributes()?:$this->labelAttributes;

			//Insert element in label for checkbox and radio inputs
			if(in_array($sType,array('checkbox','radio'))){
				$sLabel = $this->renderElement($oElement).$sLabel;
				if(empty($aLabelAttributes['class']))$aLabelAttributes['class'] = $sType;
				elseif(strpos($aLabelAttributes['class'], $sType) === false)$aLabelAttributes['class'] .= ' '.$sType;
			}
			elseif(empty($aLabelAttributes['class']))$aLabelAttributes['class'] = 'control-label';
			elseif(strpos($aLabelAttributes['class'], 'control-label') === false)$aLabelAttributes['class'] .= ' control-label';

			$oLabelHelper = $this->getLabelHelper();

			$slabelOpen  = $oLabelHelper->openTag($aLabelAttributes);
			$sLabelClose = $oLabelHelper->closeTag();
		}
		return $slabelOpen.$sLabel.$sLabelClose;
	}

	/**
	 * Render element markup
	 * @param \Zend\Form\ElementInterface $oElement
	 * @return string
	 */
	protected function renderElement(\Zend\Form\ElementInterface $oElement){
		//Render element
		$sElementMarkup = $this->getElementHelper()->render($oElement);

		//Process twitter bootsrap options
		if($aOptions = $oElement->getOption('twb')){
			//Prepend / Append
			if(isset($aOptions['prepend']) || isset($aOptions['append'])){
				//Prepend
				$sClass = '';
				if(isset($aOptions['prepend'])){
					$sClass .= ' input-prepend';
					$sElementMarkup = $this->renderAddOn($aOptions['prepend']).$sElementMarkup;
				}
				if(isset($aOptions['append'])){
					$sClass .= ' input-append';
					$sElementMarkup .= $this->renderAddOn($aOptions['append']);
				}
				$sElementMarkup = '<div class="'.$sClass.'">'.$sElementMarkup.'</div>';
			}

			//Help-block
			if(isset($aOptions['help-block'])){
				if($oTranslator = $this->getTranslator())$aOptions['help-block'] = $oTranslator->translate($aOptions['help-block'],$this->getTranslatorTextDomain());
				$sElementMarkup .= '<span class="help-block">'.$this->getEscapeHtmlHelper()->__invoke($aOptions['help-block']).'</span>';
			}

			//Help-inline
			if(isset($aOptions['help-inline'])){
				if(isset($oTranslator) || ($oTranslator = $this->getTranslator()))$aOptions['help-inline'] = $oTranslator->translate($aOptions['help-inline'],$this->getTranslatorTextDomain());
				$sElementMarkup .= '<span class="help-inline">'.$this->getEscapeHtmlHelper()->__invoke($aOptions['help-inline']).'</span>';
			}
		}
		return $sElementMarkup;
	}

	/**
	 * Retrieve input AddOn markup
	 * @param string|array $aAddOnConfig
	 * @throws \Exception
	 * @return string
	 */
	protected function renderAddOn($aAddOnConfig){
		if(is_string($aAddOnConfig))return '<span class="add-on">'.$this->getEscapeHtmlHelper()->__invoke($aAddOnConfig).'</span>';
		elseif(is_array($aAddOnConfig) && isset($aAddOnConfig['type']))switch($aAddOnConfig['type']){
			case 'text':
				if(!isset($aAddOnConfig['content']) || !is_string($aAddOnConfig['content']))throw new \Exception('AddOn "text" type expects string "content" configuration');
				if($oTranslator = $this->getTranslator())$aAddOnConfig['content'] = $oTranslator->translate(
					$aAddOnConfig['content'],
					$this->getTranslatorTextDomain()
				);
				return '<span class="add-on">'.$this->getEscapeHtmlHelper()->__invoke($aAddOnConfig['content']).'</span>';
			case 'buttons':
				if(!isset($aAddOnConfig['buttons']) || !is_array($aAddOnConfig['buttons']))throw new \Exception('AddOn "buttons" type expects array "buttons" configuration');
				$sMarkup = '';
				foreach($aAddOnConfig['buttons'] as $sName => $oButton){
					if(is_array($oButton)){
						if(!is_string($sName) && isset($oButton['label']))$sName = $oButton['label'];
						$oButton = new \Zend\Form\Element\Button(is_string($sName)?$sName:null,$oButton);
					}
					elseif(!($oButton instanceof \Zend\Form\Element\Button))throw new \Exception(sprintf(
						'AddOn "buttons" configuration expects arrays or \Zend\Form\Element\Button, "%s" given',
						is_object($oButton)?get_class($oButton):gettype($oButton)
					));
					$sMarkup .= $this->getView()->formButton($oButton);
				}
				return $sMarkup;
			default:
				throw new \Exception('"'.$aAddOnConfig['type'].'" is not a valid AddOn type');
		}
		else throw new \Exception('AddOn config expects string or array having at least "type" key');
	}

	/**
	 * @param string $sFormLayout
	 * @throws \Exception
	 * @return \TwbBundle\Form\View\Helper\TwbBundleCollection
	 */
	public function setFormLayout($sFormLayout){
		if(!is_string($sFormLayout))throw new \Exception('Form layout expects string, "'.gettype($sFormLayout).'" given');
		$this->formLayout = $sFormLayout;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getFormLayout(){
		return $this->formLayout?:\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_VERTICAL;
	}
}