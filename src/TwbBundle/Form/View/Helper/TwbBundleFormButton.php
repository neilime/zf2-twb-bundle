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
			$sMarkup = '%s';
			if(isset($aOptions['dropdown'])){
				if($sClass = $oElement->getAttribute('class')){
					if(strpos($sClass, 'dropdown-toggle') === false)$oElement->setAttribute('class',$sClass.' dropdown-toggle');
				}
				else $oElement->setAttribute('class','dropdown-toggle');
				$sButtonContent .= '<span class="caret"></span>';
				$sMarkup = '
					<div class="btn-group">%s</div>
					<ul class="dropdown-menu">'.join(PHP_EOL,array_map(
						array($this,'renderDropdownAction'),
						empty($aOptions['actions']) || !is_array($aOptions['actions'])?array():$aOptions['actions']
					)).'</ul>
				';
			}
			return sprintf($sMarkup,parent::render($oElement, $sButtonContent));
		}
		else return parent::render($oElement, $sButtonContent);
	}

	/**
	 * Retrieve dropdown action markup
	 * @param string|array $aActionConfig
	 * @throws \Exception
	 * @return string
	 */
	protected function renderDropdownAction($aActionConfig){
		$sActionUrl = '#';
		if(is_array($aActionInfos)){
			$sActionName = $aActionInfos['name'];
			if($sActionName === '-')return '<li class="divider"></li>';
			if(!empty($aActionInfos['url']))$sActionUrl = $aActionInfos['url'];
			unset($aActionInfos['name'],$aActionInfos['url']);
			$sAttributes = $this->createAttributesString($aActionInfos);
		}
		elseif(is_string($aActionInfos)){
			if(empty($aActionInfos))throw new \Exception('Action name is empty');
			$sActionName = $aActionInfos;
			if($sActionName === '-')return '<li class="divider"></li>';
		}
		else throw new \Exception('Action config expects string or array, "'.gettype($aActionConfig).'" given');
		return '<li>
			<a href="'.$this->getEscapeHtmlAttrHelper()->__invoke($sActionUrl).'"'.(empty($sAttributes)?'':$sAttributes).'>'.$this->getEscapeHtmlHelper()->__invoke($sActionName).'</a>
		</li>';
	}
}