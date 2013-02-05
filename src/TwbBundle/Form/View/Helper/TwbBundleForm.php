<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleForm extends \Zend\Form\View\Helper\Form{
	const LAYOUT_HORIZONTAL = 'horizontal';
	const LAYOUT_INLINE = 'inline';
	const LAYOUT_SEARCH = 'search';
	const LAYOUT_VERTICAL = 'vertical';

	/**
	 * @var string
	 */
	protected $formLayout;


	/**
	 * @param  null|\Zend\Form\FormInterface $oForm
     * @param string $sFormLayout : (optionnal) default "vertical"
	 * @return string|\TwbBundle\Form\View\Helper\TwbBundleForm
	 */
	public function __invoke(\Zend\Form\FormInterface $oForm = null, $sFormLayout = self::LAYOUT_VERTICAL){
		if($sFormLayout)$this->setFormLayout($sFormLayout);
		return parent::render($oForm);
	}

    /**
     * Render a form from the provided $oForm,
     * @see \Zend\Form\View\Helper\Form::render()
     * @param \Zend\Form\FormInterface $oForm
     * @return string
     */
    public function render(\Zend\Form\FormInterface $oForm){
    	if($this->getFormLayout())$oForm->setAttribute('class', $oForm->getAttribute('class').' form-'.$this->getFormLayout());
    	if(method_exists($oForm, 'prepare'))$oForm->prepare();

    	$sFormContent = '';
    	foreach($oForm as $oElement){
    		$sFormContent .= $oElement instanceof \Zend\Form\FieldsetInterface
    			?$this->getView()->formCollection($oElement,true,$this->getFormLayout())
    			:$this->getView()->formRow($oElement,$this->getFormLayout(),null,null,$this->getFormLayout());
    	}
    	return $this->openTag($oForm).$sFormContent.$this->closeTag();
    }


    /**
     * @param string $sFormLayout
     * @throws \Exception
     * @return \TwbBundle\Form\View\Helper\TwbBundleForm
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
