<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleForm extends \Zend\Form\View\Helper\Form{
	const LAYOUT_HORIZONTAL = 'horizontal';
	const LAYOUT_INLINE = 'inline';
	const LAYOUT_SEARCH = 'search';

	/**
	 * @see \Zend\Form\View\Helper\Form::__invoke()
	 * @param \Zend\Form\FormInterface $oForm
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
    			if(!preg_match('/(\s|^)'.preg_quote($sLayoutClass,'/').'(\s|$)/',$sFormClass))$oForm->setAttribute('class',trim($sFormClass.' '.$sLayoutClass));
    		}
    		else $oForm->setAttribute('class',$sLayoutClass);
    	}

    	//Set form role
    	if(!$oForm->getAttribute('role'))$oForm->setAttribute('role','form');

    	//Define layout option to form elements
    	if($sFormLayout)foreach($oForm as $oElement){
    		if($aOptions = $oElement->getOptions()){
    			if(isset($aOptions['twb'])){
	    			if(!is_array($aOptions['twb']))throw new \LogicException('"twb" element\'s option expects an array, "'.gettype($aOptions['twb']).'" given');
	    			$aOptions['twb']['layout'] = $sFormLayout;
    			}
    			else $aOptions['twb'] = array('layout' => $sFormLayout);
    			$oElement->setOptions($aOptions);
    		}
    		else $aOptions = array('twb' => array('layout' => $sFormLayout));
    		$oElement->setOptions($aOptions);
    	}
		return parent::render($oForm);
    }
}