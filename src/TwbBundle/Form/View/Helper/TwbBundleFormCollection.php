<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleFormCollection extends \Zend\Form\View\Helper\FormCollection{
	/**
	 * @var string
	 */
	protected $formLayout;

	/**
	 * @param \Zend\Form\ElementInterface $oElement
	 * @param boolean $bWrap
	 * @param string $sLayout : (optionnal) default "vertical"
	 * @return string|\TwbBundle\Form\View\Helper\TwbBundleFormCollection
	 */
    public function __invoke(\Zend\Form\ElementInterface $oElement = null, $bWrap = true,$sFormLayout = \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL){
    	if($sFormLayout)$this->setFormLayout($sFormLayout);
    	else $this->formLayout = null;
    	return parent::__invoke($oElement,$bWrap);
    }

    /**
     * Render a collection by iterating through all fieldsets and elements
     * @param \Zend\Form\ElementInterface $oElement
     * @return string
     */
    public function render(\Zend\Form\ElementInterface $oElement){
    	$oRenderer = $this->getView();
   		// Bail early if renderer is not pluggable
    	if(!method_exists($oRenderer, 'plugin'))return '';
    	$sMarkup = '';
    	foreach($oElement->getIterator() as $oElementOrFieldSet) {
    		if($oElementOrFieldSet instanceof \Zend\Form\FieldsetInterface){
    			if(!isset($oFieldsetHelper))$oFieldsetHelper = $this->getFieldsetHelper();
    			$sMarkup .= $oFieldsetHelper($oElementOrFieldSet);
    		}
    		elseif($oElementOrFieldSet instanceof \Zend\Form\ElementInterface)$sMarkup .= $this->getView()->formRow($oElementOrFieldSet,null,null,$this->getFormLayout());
    	}

    	if($oElement instanceof \Zend\Form\Element\Collection && $oElement->shouldCreateTemplate()
    	&& ($sTemplateMarkup = $this->renderTemplate($oElement)))$sMarkup .= $sTemplateMarkup;

    	// Every collection is wrapped by a fieldset if needed
    	if($this->shouldWrap && ($sLabel = $oElement->getLabel())){
   			if(null !== ($oTranslator = $this->getTranslator()))$sLabel = $oTranslator->translate($sLabel, $this->getTranslatorTextDomain());
    		$sMarkup = sprintf(
    			'<fieldset><legend>%s</legend>%s</fieldset>',
    			$this->getEscapeHtmlHelper()->__invoke($sLabel),
    			$sMarkup
    		);
    	}
    	return $sMarkup;
    }

    /**
     * Only render a template
     * @param \Zend\Form\Element\Collection $oCollection
     * @return string
     */
    public function renderTemplate(\Zend\Form\Element\Collection $oCollection){
    	$oElementOrFieldSet = $oCollection->getTemplateElement();

    	$sTemplateMarkup = '';

    	if($oElementOrFieldSet instanceof \Zend\Form\FieldsetInterface)$sTemplateMarkup .= $this->render($oElementOrFieldSet);
    	elseif($oElementOrFieldSet instanceof \Zend\Form\ElementInterface)$sTemplateMarkup .= $this->getView()->formRow($oElementOrFieldSet,null,null,$this->getFormLayout());
    	else $sTemplateMarkup = '';
    	return sprintf(
    		'<span data-template="%s"></span>',
    		$this->getEscapeHtmlAttrHelper()->__invoke($sTemplateMarkup)
    	);
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
}