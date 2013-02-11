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
			if(!preg_match('/(\s|^)btn(\s|$)/',$sClass))$oElement->setAttribute('class',$sClass.' btn');
		}
		else $oElement->setAttribute('class','btn');

		if(null === $sButtonContent){
			$sButtonContent = $oElement->getLabel();
			if(null === $sButtonContent)throw new \Exception(sprintf(
				'%s expects either button content as the second argument, or that the element provided has a label value; neither found',
				__METHOD__
			));
			if(null !== ($oTranslator = $this->getTranslator()))$sButtonContent = $oTranslator->translate($sButtonContent, $this->getTranslatorTextDomain());
		}
		$sButtonContent = $this->getEscapeHtmlHelper()->__invoke($sButtonContent);

		if($aOptions = $oElement->getOption('twb')){

			//Icon
			if(isset($aOptions['icon'])){
				if(!is_string($aOptions['icon']))throw new \Exception('Icon configuration expects string, "'.gettype($aOptions['icon']).'" given');
				//Add icon to button content
				$sButtonContent = '<i class="'.$this->getEscapeHtmlAttrHelper()->__invoke(trim($aOptions['icon'])).'"></i> '.$sButtonContent;
			}

			//Dropdowns // dropup
			if(isset($aOptions['dropdown'],$aOptions['dropup']))throw new \Exception('dropdown & dropup options are not allowed together');
			elseif(isset($aOptions['dropdown']))$sDrop = 'dropdown';
			elseif(isset($aOptions['dropup']))$sDrop = 'dropup';
			else $sDrop = null;

			if($sDrop){

				//Dropdown button is not segmented
				if(empty($aOptions[$sDrop]['segmented'])){

					if($sClass = $oElement->getAttribute('class')){
						if(strpos($sClass, 'dropdown-toggle') === false)$oElement->setAttribute('class',$sClass.' dropdown-toggle');
					}
					else $oElement->setAttribute('class','dropdown-toggle');

					//Set dropdown toogle behavior
					$oElement->setAttribute('data-toggle','dropdown');

					//Add caret to button content and render element
					$sMarkup = $this->openTag($oElement).$sButtonContent.PHP_EOL.'<span class="caret"></span>'.$this->closeTag();
				}
				else{
					//Create caret button
					$oCaretElement = new \Zend\Form\Element\Button('caret');
					$oCaretElement->setAttributes(array(
						'class' => trim('btn dropdown-toggle '.trim(preg_replace('/(^|\s)(btn|dropdown-toggle)(\s|$)/', '', $oElement->getAttribute('class')))),
						'data-toggle' => 'dropdown'
					));

					//Add caret button to button content and render element
					$sCloseTag = $this->closeTag();
					$sMarkup = $this->openTag($oElement).$sButtonContent.$this->closeTag()
					.str_ireplace($sCloseTag, PHP_EOL.'<span class="caret"></span>'.$sCloseTag, parent::render($oCaretElement,''));
				}

				return sprintf(
					'<div class="btn-group'.($sDrop === 'dropdown'?'':' '.$sDrop).'">%s<ul class="dropdown-menu'.(empty($aOptions[$sDrop]['pull'])?'':' pull-'.$aOptions[$sDrop]['pull']).'">%s</ul></div>',
					$sMarkup,
					join(PHP_EOL,array_map(
						array($this,'renderDropAction'),
						empty($aOptions[$sDrop]['actions']) || !is_array($aOptions[$sDrop]['actions'])?array():$aOptions[$sDrop]['actions']
					))
				);
			}
		}
		return $this->openTag($oElement).$sButtonContent.$this->closeTag();
	}

	/**
	 * Retrieve drop (down or up) action markup
	 * @param string|array $aActionConfig
	 * @throws \Exception
	 * @return string
	 */
	protected function renderDropAction($aActionConfig){
		$sHref = '#';
		if(is_array($aActionConfig)){
			$sActionLabel = '';

			//Label
			if(!empty($aActionConfig['label'])){
				if(!is_string($aActionConfig['label']))throw new \Exception('Label configuration expects string, "'.gettype($aActionConfig['label']).'" given');

				$sActionLabel = $aActionConfig['label'];
				if(null !== ($oTranslator = $this->getTranslator()))$sActionLabel = $oTranslator->translate($sActionLabel, $this->getTranslatorTextDomain());
				$sActionLabel = $this->getEscapeHtmlHelper()->__invoke($sActionLabel);
			}
			//Content
			elseif(!empty($aActionConfig['content']) && is_string($aActionConfig['content']))$sActionLabel = $aActionConfig['content'];

			//Icon
			if(!empty($aActionConfig['icon'])){
				if(!is_string($aActionConfig['icon']))throw new \Exception('Icon configuration expects string, "'.gettype($aActionConfig['icon']).'" given');
				$sActionLabel = '<i class="'.$this->getEscapeHtmlAttrHelper()->__invoke(trim($aActionConfig['icon'])).'"></i> '.$sActionLabel;
			}

			if(isset($aActionConfig['href']))$sHref =  $aActionConfig['href'];
			unset($aActionConfig['label'],$aActionConfig['href']);
			$sAttributes = $this->createAttributesString($aActionConfig);
		}
		elseif(is_string($aActionConfig)){
			if(empty($aActionConfig))throw new \Exception('Action name is empty');
			$sActionLabel = $aActionConfig;
			if($sActionLabel === '-')return '<li class="divider"></li>';
			$sHref = '#'.$this->getEscapeHtmlAttrHelper()->__invoke($sActionLabel);

			if(null !== ($oTranslator = $this->getTranslator()))$sActionLabel = $oTranslator->translate($sActionLabel, $this->getTranslatorTextDomain());
			$sActionLabel = $this->getEscapeHtmlHelper()->__invoke($sActionLabel);
		}
		else throw new \Exception('Action config expects string or array, "'.gettype($aActionConfig).'" given');
		return sprintf(
			'<li><a href="%s"%s>%s</a></li>',
			$sHref,
			empty($sAttributes)?'':' '.$sAttributes,
			$sActionLabel
		);
	}
}