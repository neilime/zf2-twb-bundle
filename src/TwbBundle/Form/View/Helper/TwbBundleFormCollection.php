<?php

namespace TwbBundle\Form\View\Helper;

use Laminas\Form\Element\Collection as CollectionElement;
use Laminas\Form\View\Helper\FormCollection;
use Laminas\Form\ElementInterface;

class TwbBundleFormCollection extends FormCollection
{
    /**
     * @var string
     */
    protected static $legendFormat = '<legend%s>%s</legend>';

    /**
     * @var string
     */
    protected static $fieldsetFormat = '<fieldset%s>%s</fieldset>';

    /**
     * Attributes valid for the tag represented by this helper
     * @var array
     */
    protected $validTagAttributes = array(
        'disabled' => true
    );

    /**
     * Render a collection by iterating through all fieldsets and elements
     * @param \Laminas\Form\ElementInterface $oElement
     * @return string
     */
    public function render(ElementInterface $oElement)
    {
        $oRenderer = $this->getView();
        if (!method_exists($oRenderer, 'plugin')) {
            return '';
        }

        $bShouldWrap = $this->shouldWrap;

        $sMarkup = '';
        $sElementLayout = $oElement->getOption('twb-layout');
        if ($oElement instanceof \IteratorAggregate) {
            $oElementHelper = $this->getElementHelper();
            $oFieldsetHelper = $this->getFieldsetHelper();

            foreach ($oElement->getIterator() as $oElementOrFieldset) {
                $aOptions = $oElementOrFieldset->getOptions();
                if ($sElementLayout && empty($aOptions['twb-layout'])) {
                    $aOptions['twb-layout'] = $sElementLayout;
                    $oElementOrFieldset->setOptions($aOptions);
                }

                if ($oElementOrFieldset instanceof \Laminas\Form\FieldsetInterface) {
                    $sMarkup .= $oFieldsetHelper($oElementOrFieldset);
                } elseif ($oElementOrFieldset instanceof \Laminas\Form\ElementInterface) {
                	if ($oElementOrFieldset->getOption('twb-row-open')) {
						$sMarkup .= '<div class="row">' . "\n";
					}

					$sMarkup .= $oElementHelper($oElementOrFieldset);

					if ($oElementOrFieldset->getOption('twb-row-close')) {
						$sMarkup .= '</div>' . "\n";
					}
                }
            }
            if ($oElement instanceof \Laminas\Form\Element\Collection && $oElement->shouldCreateTemplate()) {
                $sMarkup .= $this->renderTemplate($oElement);
            }
        }

        if ($bShouldWrap) {
            if (false != ($sLabel = $oElement->getLabel())) {
                if (null !== ($oTranslator = $this->getTranslator())) {
                    $sLabel = $oTranslator->translate($sLabel, $this->getTranslatorTextDomain());
                }

                $sMarkup = sprintf(
                        static::$legendFormat, ($sAttributes = $this->createAttributesString($oElement->getLabelAttributes()? : array())) ? ' ' . $sAttributes : '', $this->getEscapeHtmlHelper()->__invoke($sLabel)
                ) . $sMarkup;
            }

            //Set form layout class
            if ($sElementLayout) {
                $sLayoutClass = 'form-' . $sElementLayout;
                if (false != ($sElementClass = $oElement->getAttribute('class'))) {
                    if (!preg_match('/(\s|^)' . preg_quote($sLayoutClass, '/') . '(\s|$)/', $sElementClass)) {
                        $oElement->setAttribute('class', trim($sElementClass . ' ' . $sLayoutClass));
                    }
                } else {
                    $oElement->setAttribute('class', $sLayoutClass);
                }
            }

            $sMarkup = sprintf(
                    static::$fieldsetFormat, ($sAttributes = $this->createAttributesString($oElement->getAttributes())) ? ' ' . $sAttributes : '', $sMarkup
            );
        }
        return $sMarkup;
    }

    /**
     * Only render a template
     *
     * @param CollectionElement $collection
     * @return string
     */
    public function renderTemplate(CollectionElement $collection)
    {
        if (false != ($sElementLayout = $collection->getOption('twb-layout'))) {
            $elementOrFieldset = $collection->getTemplateElement();
            $elementOrFieldset->setOption('twb-layout', $sElementLayout);
        }

        return parent::renderTemplate($collection);
    }
}
