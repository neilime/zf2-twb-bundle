<?php
namespace TwbBundle\Form\View\Helper;

use Zend\Form\LabelAwareInterface;
class TwbBundleFormButton extends \Zend\Form\View\Helper\FormButton
{
    /**
     * @var string
     */
    const GLYPHICON_PREPEND = 'prepend';

    /**
     * @var string
     */
    const GLYPHICON_APPEND = 'append';

    /**
     * @var string
     */
    private static $dropdownContainerFormat = '<div class="btn-group %s">%s</div>';

    /**
     * @var string
     */
    private static $dropdownToggleFormat = '%s <b class="caret"></b>';

    /**
     * @var string
     */
    private static $dropdownCaretFormat = '<button type="button" class="dropdown-toggle %s" data-toggle="dropdown"><span class="caret"></span></button>';

    /**
     * Allowed button options
     * @var array
     */
    protected static $buttonOptions = array('default', 'primary', 'success', 'info', 'warning', 'danger');

    /**
     * @see \Zend\Form\View\Helper\FormButton::render()
     * @param \Zend\Form\ElementInterface $oElement
     * @param string $sButtonContent
     * @throws \LogicException
     * @throws \Exception
     * @return string
     */
    public function render(\Zend\Form\ElementInterface $oElement, $sButtonContent = null)
    {
        if ($sClass = $oElement->getAttribute('class')) {

            if (!preg_match('/(\s|^)btn(\s|$)/', $sClass)) {
                $sClass .= ' btn';
            }

            if (!preg_match('/(\s|^)btn-.*(\s|$)/', $sClass)) {
                $sClass .= ' btn-default';
            } else {
                $bHasOption = false;
                foreach (self::$buttonOptions as $sButtonOption) {
                    if (preg_match('/(\s|^)btn-' . $sButtonOption . '.*(\s|$)/', $sClass)) {
                        $bHasOption = true;
                        break;
                    }
                }
                if (!$bHasOption) {
                    $sClass .= ' btn-default';
                }
            }
            $oElement->setAttribute('class', trim($sClass));
        } else {
            $oElement->setAttribute('class', 'btn btn-default');
        }

        //Retrieve glyphicon options
        if (null !== ($aGlyphiconOptions = $oElement->getOption('glyphicon'))) {
            $helperMethod = 'glyphicon';
        } elseif (null !== ($aGlyphiconOptions = $oElement->getOption('fontAwesome'))) {
            $helperMethod = 'fontAwesome';
        }

        //Define button content
        if (null === $sButtonContent) {
            $sButtonContent = $oElement->getLabel();
            if (null === $sButtonContent && !$aGlyphiconOptions) {
                throw new \DomainException(sprintf(
                    '%s expects either button content as the second argument, ' .
                    'or that the element provided has a label value or a glyphicon option; neither found',
                    __METHOD__
                ));
            }

            if (null !== ($oTranslator = $this->getTranslator())) {
                $sButtonContent = $oTranslator->translate(
                    $sButtonContent, $this->getTranslatorTextDomain()
                );
            }
        }

        if (! $oElement instanceof LabelAwareInterface || ! $oElement->getLabelOption('disable_html_escape')) {
            $escapeHtmlHelper = $this->getEscapeHtmlHelper();
            $sButtonContent = $escapeHtmlHelper($sButtonContent);
        }

        //Glyphicon
        if ($aGlyphiconOptions) {
            if(is_scalar($aGlyphiconOptions)) {
                $aGlyphiconOptions = array(
                    'icon' => $aGlyphiconOptions,
                    'position' => self::GLYPHICON_PREPEND
                );
            } elseif(!is_array($aGlyphiconOptions)){
                throw new \LogicException('"glyphicon" button option expects a scalar value or an array, "' . gettype($aGlyphiconOptions) . '" given');
            } elseif(!is_scalar($aGlyphiconOptions['icon'])){
                throw new \LogicException('Glyphicon "icon" option expects a scalar value, "' . gettype($aGlyphiconOptions['icon']) . '" given');
            } elseif(empty($aGlyphiconOptions['position'])){
                $aGlyphiconOptions['position'] = 'prepend';
            } elseif(!is_string($aGlyphiconOptions['position'])){
                throw new \LogicException('Glyphicon "position" option expects a string, "' . gettype($aGlyphiconOptions['position']) . '" given');
            } elseif($aGlyphiconOptions['position'] !== self::GLYPHICON_PREPEND && $aGlyphiconOptions['position'] !== self::GLYPHICON_APPEND){
                throw new \LogicException('Glyphicon "position" option allows "'.self::GLYPHICON_PREPEND.'" or "'.self::GLYPHICON_APPEND.'", "' . $aGlyphiconOptions['position'] . '" given');
            }

            if ($sButtonContent) {
                if($aGlyphiconOptions['position'] === self::GLYPHICON_PREPEND) {
                    $sButtonContent = $this->getView()->{$helperMethod}($aGlyphiconOptions['icon'],isset($aGlyphiconOptions['attributes'])?$aGlyphiconOptions['attributes']:null).' '.$sButtonContent;
                }
                else {
                    $sButtonContent .= ' '.$this->getView()->{$helperMethod}($aGlyphiconOptions['icon'],isset($aGlyphiconOptions['attributes'])?$aGlyphiconOptions['attributes']:null);
                }
            }
            else{
                $sButtonContent = $this->getView()->{$helperMethod}($aGlyphiconOptions['icon'],isset($aGlyphiconOptions['attributes'])?$aGlyphiconOptions['attributes']:null);
            }
        }

        //Dropdown button
        if ($aDropdownOptions = $oElement->getOption('dropdown')) {
            if (!is_array($aDropdownOptions)) {
                throw new \LogicException('"dropdown" option expects an array, "' . gettype($aDropdownOptions) . '" given');
            }

            if (empty($aDropdownOptions['split'])) {
                //Class
                if (!preg_match('/(\s|^)dropdown-toggle(\s|$)/', $sClass = $oElement->getAttribute('class'))) {
                    $oElement->setAttribute('class', trim($sClass . ' dropdown-toggle'));
                }

                //data-toggle
                $oElement->setAttribute('data-toggle', 'dropdown');
                $sMarkup = $this->openTag($oElement) . sprintf(self::$dropdownToggleFormat, $sButtonContent) . $this->closeTag();
            } //Ad caret element
            else {
                $sMarkup = $this->openTag($oElement) . $sButtonContent . $this->closeTag() . sprintf(self::$dropdownCaretFormat, $oElement->getAttribute('class'));
            }

            //No container
            if ($oElement->getOption('disable-twb')) {
                return $sMarkup . $this->getView()->dropdown()->renderListItems($aDropdownOptions);
            }

            //Render button + dropdown
            return sprintf(
                self::$dropdownContainerFormat,
                //Drop way
                empty($aDropdownOptions['dropup']) ? '' : 'dropup',
                $sMarkup
                . $this->getView()->dropdown()->renderListItems($aDropdownOptions)
            );
        }

        return $this->openTag($oElement) . $sButtonContent . $this->closeTag();
    }
}