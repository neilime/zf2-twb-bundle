<?php

namespace TwbBundle\Form\View\Helper;

class TwbBundleFormButton extends \Zend\Form\View\Helper\FormButton {

    /**
     * @var string
     */
    const ICON_PREPEND = 'prepend';

    /**
     * @var string
     */
    const ICON_APPEND = 'append';

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
    public function render(\Zend\Form\ElementInterface $oElement, $sButtonContent = null) {
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

        // Retrieve icon options
        if (null !== ($aIconOptions = $oElement->getOption('glyphicon'))) {
            $sIconHelperMethod = 'glyphicon';
        } elseif (null !== ($aIconOptions = $oElement->getOption('fontAwesome'))) {
            $sIconHelperMethod = 'fontAwesome';
        }

        // Define button content
        if (null === $sButtonContent) {
            $sButtonContent = $oElement->getLabel();
            if (null === $sButtonContent && !$aIconOptions) {
                throw new \DomainException(sprintf(
                        '%s expects either button content as the second argument, ' .
                        'or that the element provided has a label value or a icon option; neither found', __METHOD__
                ));
            }

            if (null !== ($oTranslator = $this->getTranslator())) {
                $sButtonContent = $oTranslator->translate(
                        $sButtonContent, $this->getTranslatorTextDomain()
                );
            }
        }

        if (!$oElement instanceof \Zend\Form\LabelAwareInterface || !$oElement->getLabelOption('disable_html_escape')) {
            $oEscapeHtmlHelper = $this->getEscapeHtmlHelper();
            $sButtonContent = $oEscapeHtmlHelper($sButtonContent);
        }

        // Manage icon
        if ($aIconOptions) {
            if (is_scalar($aIconOptions)) {
                $aIconOptions = array(
                    'icon' => $aIconOptions,
                    'position' => self::ICON_PREPEND
                );
            } elseif (!is_array($aIconOptions)) {
                throw new \LogicException('Button "icon" option expects a scalar value or an array, "' . gettype($aIconOptions) . '" given');
            } elseif (!is_scalar($aIconOptions['icon'])) {
                throw new \LogicException('Button "icon" option expects a scalar value, "' . gettype($aIconOptions['icon']) . '" given');
            } elseif (empty($aIconOptions['position'])) {
                $aIconOptions['position'] = 'prepend';
            } elseif (!is_string($aIconOptions['position'])) {
                throw new \LogicException('Icon "position" option expects a string, "' . gettype($aIconOptions['position']) . '" given');
            } elseif ($aIconOptions['position'] !== self::ICON_PREPEND && $aIconOptions['position'] !== self::ICON_APPEND) {
                throw new \LogicException('Icon "position" option allows "' . self::ICON_PREPEND . '" or "' . self::ICON_APPEND . '", "' . $aIconOptions['position'] . '" given');
            }

            if ($sButtonContent) {
                if ($aIconOptions['position'] === self::ICON_PREPEND) {
                    $sButtonContent = $this->getView()->{$sIconHelperMethod}($aIconOptions['icon'], isset($aIconOptions['attributes']) ? $aIconOptions['attributes'] : null) . ' ' . $sButtonContent;
                } else {
                    $sButtonContent .= ' ' . $this->getView()->{$sIconHelperMethod}($aIconOptions['icon'], isset($aIconOptions['attributes']) ? $aIconOptions['attributes'] : null);
                }
            } else {
                $sButtonContent = $this->getView()->{$sIconHelperMethod}($aIconOptions['icon'], isset($aIconOptions['attributes']) ? $aIconOptions['attributes'] : null);
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
                    empty($aDropdownOptions['dropup']) ? '' : 'dropup', $sMarkup
                    . $this->getView()->dropdown()->renderListItems($aDropdownOptions)
            );
        }

        return $this->openTag($oElement) . $sButtonContent . $this->closeTag();
    }

}
