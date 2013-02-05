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