<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleForm extends \Zend\Form\View\Helper\Form{
	const LAYOUT_HORIZONTAL = 'horizontal';
	const LAYOUT_INLINE = 'inline';
	const LAYOUT_SEARCH = 'search';
	const LAYOUT_VERTICAL = 'vertical';

    /**
     * Render a form from the provided $oForm,
     * @see \Zend\Form\View\Helper\Form::render()
     * @param \Zend\Form\FormInterface $oForm
     * @return string
     */
    public function render(\Zend\Form\FormInterface $oForm, $sFormLayout = self::LAYOUT_VERTICAL){

    	//Set form layout class
    	if(is_string($sFormLayout)){
    		$sLayoutClass = 'form-'.$sFormLayout;
    		if($sFormClass = $oForm->getAttribute('class')){
    			if(strpos($sFormClass, $sLayoutClass) === false)$oForm->setAttribute('class',trim($sFormClass.' '.$sLayoutClass));
    		}
    		else $oForm->setAttribute('class',$sLayoutClass);
    	}
    	if(method_exists($oForm, 'prepare'))$oForm->prepare();

    	$sFormContent = '';
    	foreach($oForm as $oElement){
    		$sFormContent .= $oElement instanceof \Zend\Form\FieldsetInterface
    			?$this->getView()->formCollection($oElement,true,$sFormLayout)
    			:$this->getView()->formRow($oElement,null,null,$sFormLayout);
    	}
    	return $this->openTag($oForm).$sFormContent.$this->closeTag();
    }
}
