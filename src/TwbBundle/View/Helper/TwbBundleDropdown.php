<?php
namespace TwbBundle\View\Helper;
class TwbBundleDropDown extends \Zend\Form\View\Helper\AbstractHelper{
	const TYPE_ITEM_HEADER = 'header';
	const TYPE_ITEM_DIVIDER = '---';
	const TYPE_ITEM_LINK = 'link';

	/**
	 * @var string
	 */
	private static $dropdownContainerFormat = '<div %s>%s</div>';

	/**
	 * @var string
	 */
	private static $dropdownToggleFormat = '<a %s>%s <b class="caret"></b></a>';

	/**
	 * @var string
	 */
	private static $dropdownListFormat = '<ul %s>%s</ul>';

	/**
	 * @var string
	 */
	private static $dropdownItemContainerFormat = '<li %s>%s</li>';

	/**
	 * @var string
	 */
	private static $dropdownItemFormats = array(
		self::TYPE_ITEM_LINK => '<a %s>%s</a>'
	);

	/**
	 * @param array $aDropdownOptions
	 * @return \TwbBundle\View\Helper\TwbBundleDropDown|string
	 */
    public function __invoke(array $aDropdownOptions = null){
		return $aDropdownOptions?$this->render($aDropdownOptions):$this;
    }

	/**
	 * Render dropdown markup
	 * @param array $aDropdownOptions
	 * @throws \LogicException
	 * @return string
	 */
	public function render(array $aDropdownOptions){
		// ### Dropdown container attributes ###
		if(empty($aDropdownOptions['attributes']))$aDropdownOptions['attributes'] = array('class' => 'dropdown');
		else{
			if(!is_array($aDropdownOptions['attributes']))throw new \LogicException('"attributes" option expects an array, "'.gettype($aDropdownOptions['attributes']).'" given');
			if(empty($aDropdownOptions['attributes']['class']))$aDropdownOptions['attributes']['class'] = 'dropdown';
			elseif(!preg_match('/(\s|^)dropdown(\s|$)/',$aDropdownOptions['attributes']['class']))$aDropdownOptions['attributes']['class'] .= ' dropdown';
		}

		// ### Render dropdown ###

		return sprintf(
			self::$dropdownContainerFormat,
			//Container attributes
			$this->createAttributesString($aDropdownOptions['attributes']),
			//Toggle
			$this->renderToggle($aDropdownOptions).
			//List items
			$this->renderListItems($aDropdownOptions)
		);
	}

	/**
	 * Render dropdown toggle markup
	 * @param array $aDropdownOptions
	 * @throws \LogicException
	 * @return string
	 */
	public function renderToggle(array $aDropdownOptions){
		// ### Dropdown toggle ###
		if(empty($aDropdownOptions['label']))$aDropdownOptions['label'] = '';
		elseif(!is_scalar($aDropdownOptions['label']))throw new \InvalidArgumentException('"label" option expects a scalar value, "'.gettype($aDropdownOptions['label']).'" given');
		elseif(($oTranslator = $this->getTranslator()))$aDropdownOptions['label'] = $oTranslator->translate($aDropdownOptions['label'],$this->getTranslatorTextDomain());

		//Dropdown toggle attributes

		//Class
		if(empty($aDropdownOptions['toggle_attributes']))$aDropdownOptions['toggle_attributes'] = array('class' => 'sr-only dropdown-toggle');
		else{
			if(!is_array($aDropdownOptions['toggle_attributes']))throw new \InvalidArgumentException('"toggle_attributes" option expects an array, "'.gettype($aDropdownOptions['toggle_attributes']).'" given');
			if(empty($aDropdownOptions['toggle_attributes']['class']))$aDropdownOptions['toggle_attributes']['class'] = 'sr-only dropdown-toggle';
			else{
				if(!preg_match('/(\s|^)sr-only(\s|$)/',$aDropdownOptions['toggle_attributes']['class']))$aDropdownOptions['toggle_attributes']['class'] .= ' sr-only';
				if(!preg_match('/(\s|^)dropdown-toggle(\s|$)/',$aDropdownOptions['toggle_attributes']['class']))$aDropdownOptions['toggle_attributes']['class'] .= ' dropdown-toggle';
			}
		}

		//data-toggle
		if(empty($aDropdownOptions['toggle_attributes']['data-toggle']))$aDropdownOptions['toggle_attributes']['data-toggle'] = 'dropdown';

		//Role
		if(empty($aDropdownOptions['toggle_attributes']['role']))$aDropdownOptions['toggle_attributes']['role'] = 'button';

		//Href
		if(empty($aDropdownOptions['toggle_attributes']['href']))$aDropdownOptions['toggle_attributes']['href'] = '#';

		//Id
		if(!empty($aDropdownOptions['name']))$aDropdownOptions['toggle_attributes']['id'] = $aDropdownOptions['name'];

		$aValidTagAttributes = $this->validTagAttributes;
		$this->validTagAttributes = array('href' => true);
		$sAttributeString = $this->createAttributesString($aDropdownOptions['toggle_attributes']);
		$this->validTagAttributes = $aValidTagAttributes;

		return sprintf(
			self::$dropdownToggleFormat,
			//Toggle attributes
			$sAttributeString,
			//Toggle label
			$this->getEscapeHtmlHelper()->__invoke($aDropdownOptions['label'])
		);
	}

	/**
	 * Render dropdown list items markup
	 * @param array $aDropdownOptions
	 * @throws \LogicException
	 * @return string
	 */
	public function renderListItems(array $aDropdownOptions){
		if(!isset($aDropdownOptions['items']))throw new \LogicException(__METHOD__.' expects "items" option');
		if(!is_array($aDropdownOptions['items']))throw new \LogicException('"items" option expects an array, "'.gettype($aDropdownOptions['items']).'" given');


		// ### Dropdown list attributes ###

		//Class
		if(empty($aDropdownOptions['list_attributes']))$aDropdownOptions['list_attributes'] = array('class' => 'dropdown-menu');
		else{
			if(!is_array($aDropdownOptions['list_attributes']))throw new \LogicException('"list_attributes" option expects an array, "'.gettype($aDropdownOptions['list_attributes']).'" given');
			if(empty($aDropdownOptions['list_attributes']['class']))$aDropdownOptions['list_attributes']['class'] = 'dropdown-menu';
			elseif(!preg_match('/(\s|^)dropdown-menu(\s|$)/',$aDropdownOptions['list_attributes']['class']))$aDropdownOptions['list_attributes']['class'] .= ' dropdown-menu';
		}

		//Role
		if(empty($aDropdownOptions['list_attributes']['role']))$aDropdownOptions['list_attributes']['role'] = 'menu';


		//Id
		if(!empty($aDropdownOptions['name']))$aDropdownOptions['list_attributes']['aria-labelledby'] = $aDropdownOptions['name'];

		// ### Items ###

		$sItems = '';
		foreach($aDropdownOptions['items'] as $sKey => $aItemOptions){
			if(!is_array($aItemOptions)){
				if(!is_scalar($aItemOptions))throw new \LogicException('item option expects an array or a scalar value, "'.gettype($aItemOptions).'" given');
				$aItemOptions = $aItemOptions === self::TYPE_ITEM_DIVIDER
				//Divider
				?array('type' => self::TYPE_ITEM_DIVIDER)
				//Link
				:array(
					'label' => $aItemOptions,
					'type' => self::TYPE_ITEM_LINK,
					'item_attributes' => array('href' => is_string($sKey)?$sKey:null)
				);
			}
			else{
				if(!isset($aItemOptions['label']))$aItemOptions['label'] = is_string($sKey)?$sKey:null;
				if(!isset($aItemOptions['type']))$aItemOptions['type'] = self::TYPE_ITEM_LINK;
			}
			$sItems .= $this->renderItem($aItemOptions).PHP_EOL;
		}

		return sprintf(
			self::$dropdownListFormat,
			//List attributes
			$this->createAttributesString($aDropdownOptions['list_attributes']),
			//Items
			$sItems
		);
	}

	/**
	 * Render dropdown list item markup
	 * @param array $aItemOptions
	 * @throws \LogicException
	 * @return string
	 */
	protected function renderItem($aItemOptions){
		if(empty($aItemOptions['type']))throw new \LogicException(__METHOD__.' expects "type" option');


		//Item container attributes
		if(empty($aItemOptions['attributes']))$aItemOptions['attributes'] = array();
		elseif(!is_array($aItemOptions['attributes']))throw new \LogicException('"attributes" option expects an array, "'.gettype($aItemOptions['attributes']).'" given');

		//Role
		if(empty($aItemOptions['attributes']['role']))$aItemOptions['attributes']['role'] = 'presentation';

		$sItemContent = '';
		switch($aItemOptions['type']){
			case self::TYPE_ITEM_HEADER:
				//Define item container "header" class
				if(empty($aItemOptions['attributes']['class']))$aItemOptions['attributes']['class'] = 'dropdown-header';
				elseif(!preg_match('/(\s|^)dropdown-header(\s|$)/',$aItemOptions['attributes']['class']))$aItemOptions['attributes']['class'] .= ' dropdown-header';

				//Header label
				if(empty($aItemOptions['label']))throw new \LogicException('"'.$aItemOptions['type'].'" item expects "label" option');
				if(!is_scalar($aItemOptions['label']))throw new \LogicException('"label" option expect scalar value, "'.gettype($aItemOptions['label']).'" given');
				elseif(($oTranslator = $this->getTranslator()))$aItemOptions['label'] = $oTranslator->translate($aItemOptions['label'],$this->getTranslatorTextDomain());

				$sItemContent = $this->getEscapeHtmlHelper()->__invoke($aItemOptions['label']);
				break;
			case self::TYPE_ITEM_DIVIDER:
				//Define item container "divider" class
				if(empty($aItemOptions['attributes']['class']))$aItemOptions['attributes']['class'] = 'divider';
				elseif(!preg_match('/(\s|^)divider(\s|$)/',$aItemOptions['attributes']['class']))$aItemOptions['attributes']['class'] .= ' divider';

				$sItemContent = '';
				break;

			case self::TYPE_ITEM_LINK:
				if(empty($aItemOptions['label']))throw new \LogicException('"'.$aItemOptions['type'].'" item expects "label" option');
				if(!is_scalar($aItemOptions['label']))throw new \LogicException('"label" option expect scalar value, "'.gettype($aItemOptions['label']).'" given');
				elseif(($oTranslator = $this->getTranslator()))$aItemOptions['label'] = $oTranslator->translate($aItemOptions['label'],$this->getTranslatorTextDomain());

				//Item attributes

				//Role
				if(empty($aItemOptions['item_attributes']['role']))$aItemOptions['item_attributes']['role'] = 'menuitem';

				//Tab index
				if(!isset($aItemOptions['item_attributes']['tabindex']))$aItemOptions['item_attributes']['tabindex'] = '-1';

				//Href
				if(!isset($aItemOptions['item_attributes']['href']))$aItemOptions['item_attributes']['href'] = '#';

				$aValidTagAttributes = $this->validTagAttributes;
				$this->validTagAttributes = array('href' => true);
				$sAttributeString = $this->createAttributesString($aItemOptions['item_attributes']);
				$this->validTagAttributes = $aValidTagAttributes;

				$sItemContent = sprintf(
					self::$dropdownItemFormats[self::TYPE_ITEM_LINK],
					$sAttributeString,
					$this->getEscapeHtmlHelper()->__invoke($aItemOptions['label'])
				);
				break;
		}

		return sprintf(
			self::$dropdownItemContainerFormat,
			$this->createAttributesString($aItemOptions['attributes']),
			$sItemContent
		);
	}
}