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
    public function __invoke(\Zend\Form\ElementInterface $oElement = null, $bWrap = true,$sFormLayout = \TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_VERTICAL){
    	if($sFormLayout)$this->setFormLayout($sFormLayout);
    	return parent::__invoke($oElement,$bWrap);
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