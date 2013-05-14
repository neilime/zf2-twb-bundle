<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleFormRow extends \Zend\Form\View\Helper\FormRow{
	/**
	 * @var \Zend\Form\Factory
	 */
	protected $formFactory;

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
	public function __invoke(\Zend\Form\ElementInterface $oElement = null, $sLabelPosition = null, $bRenderErrors = null,$sFormLayout = \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL){
		if($sFormLayout)$this->setFormLayout($sFormLayout);
		else $this->formLayout = null;
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
			$bElementInLabel = in_array($sType,array('checkbox'));

			if(
				$bIsInlineForm && !$bElementInLabel
				&& !$oElement->getAttribute('placeholder')
				&& ($sLabel = $oElement->getLabel())
			)$oElement->setAttribute('placeholder',$sLabel);

			//Buttons
			elseif(in_array($sType,array('submit','button'))){
				if($sClass = $oElement->getAttribute('class')){
					if(!preg_match('/(\s|^)btn(\s|$)/',$sClass))$oElement->setAttribute('class',$sClass.' btn');
				}
				else $oElement->setAttribute('class','btn');
			}

			elseif($oElement instanceof \Zend\Form\Element\MultiCheckbox){
				$aOptions = $oElement->getValueOptions();

				if(empty($aOptions))throw new Exception\DomainException(sprintf(
					'%s requires that the element has "value_options"; none found',
					__METHOD__
				));

				foreach($aOptions as $sKey => $aOption){
					if(is_scalar($aOption))$aOption = array('label' => $aOption, 'value' => $sKey);
					if(empty($aOption['label_attributes']['class']))$aOption['label_attributes']['class'] = $sType;
					elseif(!preg_match('/(\s|^)'.preg_quote($sType).'(\s|$)/',$aOption['label_attributes']['class']))$aOption['label_attributes']['class'] .= ' '.$sType;
					if($sFormLayout && !preg_match('/(\s|^)'.preg_quote($sFormLayout).'(\s|$)/',$aOption['label_attributes']['class']))$aOption['label_attributes']['class'] .= ' '.$sFormLayout;
					$aOptions[$sKey] = $aOption;
				}

				$oElement->setValueOptions($aOptions);
			}

			//Render according to layout
			switch($sFormLayout){
				case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE:
				case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_SEARCH:
					$sMarkup = ($bElementInLabel?$this->renderLabel($oElement):$this->renderElement($oElement)).$oElementErrorsHelper->render($oElement, array('class' => 'help-block'));
					break;
				case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL:

					//Validation state
					$sStateClass = '';
					if(count($oElement->getMessages()))$sStateClass = 'error';
					if(($aTwbOptions = $oElement->getOption('twb')) && !empty($aTwbOptions['state']) && $aTwbOptions['state'] !== $sStateClass)$sStateClass .= ' '.$aTwbOptions['state'];
					if(!empty($sStateClass))$sStateClass = ' '.trim($sStateClass);

					$sMarkup = '<div class="control-group'.$sStateClass.'">'.($bElementInLabel?'<div class="controls'.$sStateClass.'">'
						.$this->renderLabel($oElement)
						.$oElementErrorsHelper->render($oElement, array('class' => 'help-block')).
					'</div>':$this->renderLabel($oElement).'<div class="controls'.$sStateClass.'">'
					.$this->renderElement($oElement).$oElementErrorsHelper->render($oElement, array('class' => 'help-block')).
					'</div>').'</div>';
					break;
				default:
					$sMarkup = $this->renderLabel($oElement);
					if(!$bElementInLabel)$sMarkup .= $this->renderElement($oElement);
					$sMarkup .= $oElementErrorsHelper->render($oElement, array('class' => 'help-block'));
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

			//Translate label
			if($oTranslator = $this->getTranslator())$sLabel = $oTranslator->translate($sLabel,$this->getTranslatorTextDomain());

			$sLabel = $this->getEscapeHtmlHelper()->__invoke($sLabel);
			$aLabelAttributes = $oElement->getLabelAttributes()?:$this->labelAttributes;

			//Insert element in label for checkbox and radio inputs
			if(in_array($sType,array('checkbox','multicheckbox','radio'))){
				$sLabel = $this->renderElement($oElement).$sLabel;
				$sFormLayout = $this->getFormLayout();
				if(empty($aLabelAttributes['class'])){
					$aLabelAttributes['class'] = $sType;
					if($sFormLayout === \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE)$aLabelAttributes['class'] .= ' '.$sFormLayout;
				}
				else{
					if(!preg_match('/(\s|^)'.preg_quote($sType).'(\s|$)/',$sClass))$aLabelAttributes['class'] .= ' '.$sType;
					if($sFormLayout === \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE && !preg_match('/(\s|^)'.preg_quote($sFormLayout).'(\s|$)/',$sClass))$aLabelAttributes['class'] .= ' '.$sFormLayout;
					$aLabelAttributes['class'] = trim($aLabelAttributes['class']);
				}
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
		if(is_scalar($aAddOnConfig))return '<span class="add-on">'.$this->getEscapeHtmlHelper()->__invoke($aAddOnConfig).'</span>';
		elseif(is_array($aAddOnConfig) && isset($aAddOnConfig['type']))switch($aAddOnConfig['type']){
			case 'text':
				if(!isset($aAddOnConfig['text']) || !is_scalar($aAddOnConfig['text']))throw new \Exception('AddOn "text" type expects string "text" configuration');
				if($oTranslator = $this->getTranslator())$aAddOnConfig['text'] = $oTranslator->translate(
					$aAddOnConfig['text'],
					$this->getTranslatorTextDomain()
				);
				return '<span class="add-on">'.$this->getEscapeHtmlHelper()->__invoke($aAddOnConfig['text']).'</span>';
			case 'icon':
				if(!isset($aAddOnConfig['icon']) || !is_string($aAddOnConfig['icon']))throw new \Exception('AddOn "icon" type expects string "icon" configuration');
				return '<span class="add-on"><i class="'.$this->getEscapeHtmlAttrHelper()->__invoke(trim($aAddOnConfig['icon'])).'"></i></span>';
				break;
			case 'buttons':
				if(!isset($aAddOnConfig['buttons']) || !is_array($aAddOnConfig['buttons']))throw new \Exception('AddOn "buttons" type expects array "buttons" configuration');
				$sMarkup = '';
				foreach($aAddOnConfig['buttons'] as $sName => $oButton){
					if(is_array($oButton)){
						if(!isset($oButton['type']))$oButton['type'] = 'button';
						if(!isset($oButton['name']) && is_scalar($sName))$oButton['name'] = $sName;
						$oButton = $this->getFormFactory()->createElement($oButton);
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
		return $this->formLayout;
	}

	/**
	 * Retrieve composed form factory, lazy-loads one if none present
	 * @return \Zend\Form\Factory
	 */
	public function getFormFactory(){
		if(null === $this->formFactory)$this->setFormFactory(new \Zend\Form\Factory());
		return $this->formFactory;
	}

	/**
	 * Compose a form factory to use when calling add() with a non-element/fieldset
	 * @param \Zend\Form\Factory $oFactory
	 * @return Form
	 */

	/**
	 * Compose a form factory to create AddOn buttons
	 * @param \Zend\Form\Factory $oFactory
	 * @return \TwbBundle\Form\View\Helper\TwbBundleFormRow
	 */
	public function setFormFactory(\Zend\Form\Factory $oFactory){
		$this->formFactory = $oFactory;
		return $this;
	}
}