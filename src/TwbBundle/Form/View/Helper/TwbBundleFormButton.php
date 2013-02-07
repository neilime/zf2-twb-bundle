<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleFormButton extends \Zend\Form\View\Helper\FormButton{
	/**
	 * Render a form <button> element from the provided $oElement, using content from $sButtonContent or the element's "label" attribute
	 * @param \Zend\Form\ElementInterface $oElement
	 * @param null|string $sButtonContent
	 * @return string
	 */
	public function render(\Zend\Form\ElementInterface $oElement, $sButtonContent = null){
		if($sClass = $oElement->getAttribute('class')){
			if(strpos($sClass, 'btn') === false)$oElement->setAttribute('class',$sClass.' btn');
		}
		else $oElement->setAttribute('class','btn');
		if($aOptions = $oElement->getOptions('twb')){
			if(isset($aOptions['dropdown'])){

				//Dropdown button is not segmented
				if(empty($aOptions['dropdown']['segmented'])){

					if($sClass = $oElement->getAttribute('class')){
						if(strpos($sClass, 'dropdown-toggle') === false)$oElement->setAttribute('class',$sClass.' dropdown-toggle');
					}
					else $oElement->setAttribute('class','dropdown-toggle');

					//Set dropdown toogle behavior
					$oElement->setAttribute('data-toggle','dropdown');

					$sCloseTag = $this->closeTag();
					//Render element and insert caret
					$sElementMarkup = str_ireplace(
						$sCloseTag,
						PHP_EOL.'<span class="caret"></span>'.$sCloseTag,
						parent::render($oElement, $sButtonContent)
					);
				}
				else{
					//Create caret button
					$oCaretElement = new \Zend\Form\Element\Button('caret');
					$oCaretElement->setAttributes(array(
						'class' => 'btn dropdown-toggle',
						'data-toggle' => 'dropdown'
					));

					//Render element and insert caret
					$sCloseTag = $this->closeTag();
					$sElementMarkup = parent::render($oElement, $sButtonContent).str_ireplace(
						$sCloseTag,
						PHP_EOL.'<span class="caret"></span>'.$sCloseTag,
						parent::render($oCaretElement,'')
					);
				}
				return sprintf(
					'<div class="btn-group">%s<ul class="dropdown-menu">%s</ul></div>',
					$sElementMarkup,
					join(PHP_EOL,array_map(
						array($this,'renderDropdownAction'),
						empty($aOptions['dropdown']['actions']) || !is_array($aOptions['dropdown']['actions'])?array():$aOptions['dropdown']['actions']
					))
				);
			}
		}
		return parent::render($oElement, $sButtonContent);
	}

	/**
	 * Retrieve dropdown action markup
	 * @param string|array $aActionConfig
	 * @throws \Exception
	 * @return string
	 */
	protected function renderDropdownAction($aActionConfig){
		$sActionUrl = '#';
		if(is_array($aActionConfig)){
			$sActionName = $aActionConfig['name'];
			if($sActionName === '-')return '<li class="divider"></li>';
			if(!empty($aActionConfig['url']))$sActionUrl = $aActionConfig['url'];
			unset($aActionConfig['name'],$aActionConfig['url']);
			$sAttributes = $this->createAttributesString($aActionConfig);
		}
		elseif(is_string($aActionConfig)){
			if(empty($aActionConfig))throw new \Exception('Action name is empty');
			$sActionName = $aActionConfig;
			if($sActionName === '-')return '<li class="divider"></li>';
		}
		else throw new \Exception('Action config expects string or array, "'.gettype($aActionConfig).'" given');
		return sprintf(
			'<li><a href="%s"%s>%s</a></li>',
			$this->getEscapeHtmlAttrHelper()->__invoke($sActionUrl),
			empty($sAttributes)?'':' '.$sAttributes,
			$this->getEscapeHtmlHelper()->__invoke($sActionName)
		);
	}
}