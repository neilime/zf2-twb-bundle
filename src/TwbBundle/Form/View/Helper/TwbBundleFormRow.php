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
			if($bIsInlineForm)$oElement->setAttribute('placeholder', $oElement->getLabel());

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
			elseif($sType === 'submit'){
				if($sClass = $oElement->getAttribute('class')){
					if(strpos($sClass, 'btn') === false)$oElement->setAttribute('class',$sClass.' btn');
				}
				else $oElement->setAttribute('class','btn');
			}

			//Render according to layout
			switch($sFormLayout){
				case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_VERTICAL:
					$sMarkup = $this->renderLabel($oElement);
					if(!in_array($sType,array('checkbox','radio')))$sMarkup .= $this->renderElement($oElement);
					$sMarkup .= $oElementErrorsHelper->render($oElement, array('class' => 'help-block'));
					break;
				case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE:
				case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_SEARCH:
					$sMarkup = $this->renderElement($oElement).$oElementErrorsHelper->render($oElement, array('class' => 'help-block'));
					break;
				case \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL:
					$sMarkup = '
						<div class="control-group'.(count($oElement->getMessages()?' error':'')).'">
					'.(in_array($sType,array('checkbox','radio'))?'
						<div class="controls">'
							.$this->renderLabel($oElement)
							.$oElementErrorsHelper->render($oElement, array('class' => 'help-block')).
						'</div>
					':$this->renderLabel($oElement).'
							<div class="controls">'
								.$this->renderElement($oElement).$oElementErrorsHelper->render($oElement, array('class' => 'help-block')).
							'</div>
					').'</div>';
					break;
			}
		}
		return $sMarkup;
	}

	/**
	 * Retrieve label markup
	 * @param \Zend\Form\ElementInterface $oElement
	 * @return string
	 */
	protected function renderLabel(\Zend\Form\ElementInterface $oElement){
		$slabelOpen = $sLabelClose = '';
		if($sLabel = $oElement->getLabel()){
			$sLabel = $this->getEscapeHtmlHelper()->__invoke($sLabel);
			$aLabelAttributes = $oElement->getLabelAttributes()?:$this->labelAttributes;

			//Insert element in label for checkbox and radio inputs
			if(in_array($sType = $oElement->getAttribute('type'),array('checkbox','radio'))){
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
			if(isset($options['prepend']) || isset($options['append'])) {
				$template = $this->bootstrapTemplates['prependAppend'];
				$class = '';
				$prepend = '';
				$append = '';
				if (isset($options['prepend'])) {
					$class .= 'input-prepend ';
					if (!is_array($options['prepend'])) {
						$options['prepend'] = (array) $options['prepend'];
					}
					foreach ($options['prepend'] AS $p) {
						$prepend .= '<span class="add-on">' . $escapeHtmlHelper($p) . '</span>';
					}
				}
				if (isset($options['append'])) {
					$class .= 'input-append ';
					if (!is_array($options['append'])) {
						$options['append'] = (array) $options['append'];
					}
					foreach ($options['append'] AS $a) {
						$append .= '<span class="add-on">' . $escapeHtmlHelper($a) . '</span>';
					}
				}

				$elementString = sprintf($template,
						$class,
						$prepend,
						$elementString,
						$append);

			}
			if(isset($aOptions['help'])){
				if($oTranslator = $this->getTranslator())$aOptions['help'] = $oTranslator->translate($aOptions['help'],$this->getTranslatorTextDomain());
				$sElementMarkup .= '<span class="help-block">'.$this->getEscapeHtmlHelper()->__invoke($aOptions['help']).'</span>';
			}
		}
		return $sElementMarkup;
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