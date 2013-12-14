<?php
namespace TwbBundle\View\Helper;
class TwbBundleBadge extends \Zend\Form\View\Helper\AbstractHelper{
	/**
	 * @var string
	 */
	private static $badgeFormat = '<span %s>%s</span>';

	/**
	 * Invoke helper as functor, proxies to {@link render()}.
	 * @param string $sBadgeMessage
	 * @param array $aBadgeAttributes : [optionnal]
	 * @return string|\TwbBundle\View\Helper\TwbBundleBadge
	 */
    public function __invoke($sBadgeMessage = null, array $aBadgeAttributes = null){
        if(!$sBadgeMessage)return $this;
        return $this->render($sBadgeMessage,$aBadgeAttributes);
    }

    /**
     * Retrieve badge markup
     * @param string $sBadgeMessage
     * @param  array $aBadgeAttributes : [optionnal]
     * @throws \InvalidArgumentException
     * @return string
     */
	public function render($sBadgeMessage,array $aBadgeAttributes = null){
		if(!is_scalar($sBadgeMessage))throw new \InvalidArgumentException('Badge message expects a scalar value, "'.gettype($sBadgeMessage).'" given');

		if(empty($aBadgeAttributes))$aBadgeAttributes = array('class' => 'badge');
		else{
			if(empty($aBadgeAttributes['class']))$aBadgeAttributes['class'] = 'badge';
			elseif(!preg_match('/(\s|^)badge(\s|$)/',$aBadgeAttributes['class']))$aBadgeAttributes['class'] .= ' badge';
		}
		if(null !== ($oTranslator = $this->getTranslator()))$sBadgeMessage = $oTranslator->translate($sBadgeMessage, $this->getTranslatorTextDomain());
		return sprintf(
			self::$badgeFormat,
			$this->createAttributesString($aBadgeAttributes),
			$sBadgeMessage
		);
	}
}
