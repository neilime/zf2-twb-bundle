<?php
namespace TwbBundle\View\Helper;
class TwbBundleAlert extends \Zend\I18n\View\Helper\AbstractTranslatorHelper{
	/**
	 * @var \Zend\View\Helper\EscapeHtmlAttr
	 */
	protected $escapeHtmlAttrHelper;

	/**
	 * Invoke helper as functor, proxies to {@link render()}.
	 * @param string $sAlertMessage
	 * @param string $sAlertClass
	 * @param boolean $bCloseAlert
	 * @return string|\TwbBundle\View\Helper\TwbBundleAlert
	 */
    public function __invoke($sAlertMessage, $sAlertClass = null, $bCloseAlert = true){
        if(!$sAlert)return $this;
        return $this->render($sAlert,$sAlertClass,$bCloseAlert);
    }

    /**
     * Retrieve alert markup
     * @param string $sAlertMessage
     * @param string $sAlertClass : (optionnal)
     * @param boolean $bCloseAlert
     * @throws \Exception
     * @return string
     */
	public function render($sAlertMessage, $sAlertClass = null, $bCloseAlert = true){
		if(!is_string($sAlertMessage))throw new \Exception('Alert message expects string, "'.gettype($sAlertMessage).'" given');
		if($sAlertClass && !is_string($sAlertClass))throw new \Exception('Alert class expects string, "'.gettype($sAlertClass).'" given');

		if(null !== ($oTranslator = $this->getTranslator()))$sAlertMessage = $oTranslator->translate($sAlertMessage, $this->getTranslatorTextDomain());

		return sprintf(
			'<div class="alert%s">%s%s</div>',
			$sAlertClass?' '.$this->getEscapeHtmlAttrHelper()->__invoke($sAlertClass):'',
			$bCloseAlert?'<button type="button" class="close" data-dismiss="alert">&times;</button>':'',
			$sAlertMessage
		);
	}

	/**
	 * Retrieve the escapeHtmlAttr helper
	 * @return \Zend\View\Helper\EscapeHtmlAttr
	 */
	protected function getEscapeHtmlAttrHelper(){
		if($this->escapeHtmlAttrHelper)return $this->escapeHtmlAttrHelper;
		if(method_exists($this->view, 'plugin'))$this->escapeHtmlAttrHelper = $this->view->plugin('escapehtmlattr');
		if(!$this->escapeHtmlAttrHelper instanceof \Zend\View\Helper\EscapeHtmlAttr)$this->escapeHtmlAttrHelper = new \Zend\View\Helper\EscapeHtmlAttr();
		return $this->escapeHtmlAttrHelper;
	}
}