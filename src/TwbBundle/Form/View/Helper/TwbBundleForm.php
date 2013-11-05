<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleForm extends \Zend\Form\View\Helper\Form
{
    const LAYOUT_HORIZONTAL = 'horizontal';
	const LAYOUT_INLINE = 'inline';
	const LAYOUT_SEARCH = 'search';
	
	/**
	 * @var string
	 */
	private static $formRowFormat = '<div class="row">%s</div>';

    /**
     * @see \Zend\Form\View\Helper\Form::__invoke()
     * @param \Zend\Form\FormInterface $oForm
     * @param string $sFormLayout
     * @return \TwbBundle\Form\View\Helper\TwbBundleForm|string
     */
    public function __invoke(\Zend\Form\FormInterface $oForm = null, $sFormLayout = self::LAYOUT_HORIZONTAL)
    {
        if($oForm)return $this->render($oForm,$sFormLayout);
		return $this;
	}

    /**
     * Render a form from the provided $oForm,
     * @see \Zend\Form\View\Helper\Form::render()
     * @param \Zend\Form\FormInterface $oForm
     * @param string $sFormLayout
     * @return string
     */
    public function render(\Zend\Form\FormInterface $oForm, $sFormLayout = self::LAYOUT_HORIZONTAL){
	if (method_exists($oForm, 'prepare')) {
            $oForm->prepare();
        }
        
    	//Set form layout class
    	if(is_string($sFormLayout)){
    		$sLayoutClass = 'form-'.$sFormLayout;
    		if($sFormClass = $oForm->getAttribute('class')){
    			if(!preg_match('/(\s|^)'.preg_quote($sLayoutClass,'/').'(\s|$)/',$sFormClass))$oForm->setAttribute('class',trim($sFormClass.' '.$sLayoutClass));
    		}
    		else $oForm->setAttribute('class',$sLayoutClass);
    	}

    	//Set form role
    	if(!$oForm->getAttribute('role'))$oForm->setAttribute('role','form');

    	$bHasColumnSizes = false;
       	$sFormContent = '';
       	$oRenderer = $this->getView();
       	foreach($oForm as $oElement){
    		$aOptions = $oElement->getOptions();
    		if(!$bHasColumnSizes && !empty($aOptions['colunm-size']))$bHasColumnSizes = true;
	    	//Define layout option to form elements
    		if($sFormLayout){
    			$aOptions['twb-layout'] = $sFormLayout; 
	    		$oElement->setOptions($aOptions);
    		}
    		$sFormContent .= $oElement instanceof \Zend\Form\FieldsetInterface?$oRenderer->formCollection($oElement):$oRenderer->formRow($oElement);
    	}
    	if($bHasColumnSizes)$sFormContent = sprintf(self::$formRowFormat,$sFormContent);
    	return $this->openTag($oForm).$sFormContent.$this->closeTag();
    }
}
