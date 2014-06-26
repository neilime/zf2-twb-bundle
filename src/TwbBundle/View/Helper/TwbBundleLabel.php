<?php
namespace TwbBundle\View\Helper;
class TwbBundleLabel extends \Zend\Form\View\Helper\AbstractHelper{
	/**
	 * @var string
	 */
	private static $labelFormat = '<%s %s>%s</%1$s>';

    /**
     * @var string
     */
    protected $tagName = 'span';

    /**
     * @var array
     */
    protected $validTagAttributes = array(
        'href' => true,
    );

	/**
	 * Invoke helper as functor, proxies to {@link render()}.
	 * @param string $sLabelMessage
	 * @param string|array $aLabelAttributes : [optionnal] if string, label class
	 * @return string|\TwbBundle\View\Helper\TwbBundleAlert
	 */
    public function __invoke($sLabelMessage = null, $aLabelAttributes = 'label-default'){
        if(!$sLabelMessage)return $this;
        return $this->render($sLabelMessage,$aLabelAttributes);
    }

    /**
     * Retrieve label markup
     * @param string $sLabelMessage
     * @param  string|array $aLabelAttributes : [optionnal] if string, label class
     * @throws \InvalidArgumentException
     * @return string
     */
	public function render($sLabelMessage, $aLabelAttributes = 'label-default'){
		if(!is_scalar($sLabelMessage))throw new \InvalidArgumentException('Label message expects a scalar value, "'.gettype($sLabelMessage).'" given');
		if(empty($aLabelAttributes))throw new \InvalidArgumentException('Label attributes are empty');
		if(is_string($aLabelAttributes))$aLabelAttributes = array('class' => $aLabelAttributes);
		elseif(!is_array($aLabelAttributes))throw new \InvalidArgumentException('Label attributes expects a string or an array, "'.gettype($aLabelAttributes).'" given');
		elseif(empty($aLabelAttributes['class']))throw new \InvalidArgumentException('Label "class" attribute is empty');
		elseif(!is_string($aLabelAttributes['class']))throw new \InvalidArgumentException('Label "class" attribute expects string, "'.gettype($aLabelAttributes).'" given');


		if(!preg_match('/(\s|^)label(\s|$)/',$aLabelAttributes['class']))$aLabelAttributes['class'] .= ' label';
		if(null !== ($oTranslator = $this->getTranslator()))$sLabelMessage = $oTranslator->translate($sLabelMessage, $this->getTranslatorTextDomain());
		return sprintf(
			self::$labelFormat,
            isset($aLabelAttributes['tagName'])?$aLabelAttributes['tagName']:$this->tagName,
			$this->createAttributesString($aLabelAttributes),
			$sLabelMessage
		);
	}
}
