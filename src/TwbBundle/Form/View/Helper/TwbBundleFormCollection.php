<?php
namespace TwbBundle\Form\View\Helper;
use Zend\Form\Element\Collection as CollectionElement;
class TwbBundleFormCollection extends \Zend\Form\View\Helper\FormCollection{

	/**
	 * @var string
	 */
	private static $legendFormat = '<legend %s>%s</legend>';

	/**
	 * @var string
	 */
	private static $fieldsetFormat = '<fieldset %s>%s</fieldset>';

	/**
	 * Attributes valid for the tag represented by this helper
	 * @var array
	 */
	protected $validTagAttributes = array(
		'disabled' => true
	);

	/**
	 * Render a collection by iterating through all fieldsets and elements
	 * @param \Zend\Form\ElementInterface $oElement
	 * @return string
	 */
	public function render(\Zend\Form\ElementInterface $oElement){
		$oRenderer = $this->getView();
		if(!method_exists($oRenderer, 'plugin'))return '';

		$sMarkup = '';
		$sTemplateMarkup = '';
		$oElementHelper = $this->getElementHelper();
		$oFieldsetHelper = $this->getFieldsetHelper();

		$sTemplateMarkup = $oElement instanceof CollectionElement && $oElement->shouldCreateTemplate()?$this->renderTemplate($oElement):'';
		foreach ($oElement->getIterator() as $oElementOrFieldset){
			if($oElementOrFieldset instanceof \Zend\Form\FieldsetInterface)$sMarkup .= $oFieldsetHelper($oElementOrFieldset);
			elseif($oElementOrFieldset instanceof \Zend\Form\ElementInterface)$sMarkup .= $oElementHelper($oElementOrFieldset);
		}

		//If $sTemplateMarkup is not empty, use it for simplify adding new element in JavaScript
		if($sTemplateMarkup)$sMarkup .= $sTemplateMarkup;

		if($this->shouldWrap && ($sLabel = $oElement->getLabel())){
			if(null !== ($oTranslator = $this->getTranslator()))$sLabel = $oTranslator->translate($sLabel, $this->getTranslatorTextDomain());
			$sMarkup = sprintf(
				self::$legendFormat,
				$this->createAttributesString($oElement->getLabelAttributes()?:array()),
				$this->getEscapeHtmlHelper()->__invoke($sLabel)
			).$sMarkup;
		}
		return sprintf(
			self::$fieldsetFormat,
			$this->createAttributesString($oElement->getAttributes()),
			$sMarkup
		);
	}
}
