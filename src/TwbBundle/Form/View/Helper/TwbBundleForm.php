<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleForm extends \Zend\Form\View\Helper\Form{
	const LAYOUT_HORIZONTAL = 'horizontal';
	const LAYOUT_INLINE = 'inline';
	const LAYOUT_SEARCH = 'search';


	/**
	 * Invoke as function
	 * @param  null|FormInterface $form
	 * @return \TwbBundle\Form\View\Helper|string
	 */
	public function __invoke(\Zend\Form\FormInterface $oForm = null, $sFormLayout = self::LAYOUT_HORIZONTAL){
		if($oForm)return $this->render($oForm,$sFormLayout);
		return $this;
	}

    /**
     * Render a form from the provided $oForm,
     * @see \Zend\Form\View\Helper\Form::render()
     * @param \Zend\Form\FormInterface $oForm
     * @return string
     */
    public function render(\Zend\Form\FormInterface $oForm, $sFormLayout = self::LAYOUT_HORIZONTAL){
    	//Set form layout class
    	if(is_string($sFormLayout)){
    		$sLayoutClass = 'form-'.$sFormLayout;
    		if($sFormClass = $oForm->getAttribute('class')){
    			if(strpos($sFormClass, $sLayoutClass) === false)$oForm->setAttribute('class',trim($sFormClass.' '.$sLayoutClass));
    		}
    		else $oForm->setAttribute('class',$sLayoutClass);
    	}
    	if(method_exists($oForm, 'prepare'))$oForm->prepare();

    	$sFormContent = $sFormActions = '';
    	foreach($oForm as $oElement){
    		if($oElement instanceof \Zend\Form\FieldsetInterface)$sFormContent .= $this->getView()->formCollection(
    			$oElement,
    			method_exists($oElement,'useAsBaseFieldset')?!$oElement->useAsBaseFieldset():true,
    			$sFormLayout
    		);
    		else{
    			$aOptions = $oElement->getOption('twb');
    			if(empty($aOptions['formAction']))$sFormContent .= $this->getView()->formRow($oElement,null,null,$sFormLayout);
    			else $sFormActions .= $this->getView()->formRow($oElement,null,null,'');
    		}
    	}
    	if(!empty($sFormActions))$sFormActions = sprintf(
    		'<div class="form-actions">%s</div>',
    		$sFormActions
    	);
    	return $this->openTag($oForm).$sFormContent.$sFormActions.$this->closeTag();
    }
}
