<?php

namespace TwbBundle\Form\View\Helper;

use DomainException;
use LogicException;
use Exception;
use Laminas\Form\LabelAwareInterface;
use Laminas\Form\View\Helper\FormButton;
use Laminas\Form\ElementInterface;

class TwbBundleFormButton extends FormButton
{
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
    protected static $dropdownContainerFormat = '<div class="btn-group %s">%s</div>';

    /**
     * @var string
     */
    protected static $dropdownToggleFormat = '%s <b class="caret"></b>';

    /**
     * @var string
     */
    protected static $dropdownCaretFormat = '<button type="button" class="dropdown-toggle %s" data-toggle="dropdown"><span class="caret"></span></button>';

    /**
     * Allowed button options
     * @var array
     */
    protected static $buttonOptions = array('default', 'primary', 'success', 'info', 'warning', 'danger', 'link');

    /**
     * @see FormButton::render()
     * @param ElementInterface $oElement
     * @param string $sButtonContent
     * @throws LogicException
     * @throws Exception
     * @return string
     */
    public function render(ElementInterface $oElement, $sButtonContent = null)
    {
        if ($sClass = $oElement->getAttribute('class')) {
            if (!preg_match('/(\s|^)btn(\s|$)/', $sClass)) {
                $sClass .= ' btn';
            }

            if (!preg_match('/(\s|^)btn-.*(\s|$)/', $sClass)) {
                $sClass .= ' btn-default';
            } else {
                $bHasOption = false;
                foreach (static::$buttonOptions as $sButtonOption) {
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

        /*
         * Define button content
         */
        if (null === $sButtonContent) {
            $sButtonContent = $oElement->getLabel();

            if (!$sButtonContent) {
                $sButtonContent = $oElement->getValue();
            }

            if (null === $sButtonContent && !$aIconOptions) {
                throw new DomainException(sprintf(
                    '%s expects either button content as the second argument, ' .
                    'or that the element provided has a label value, a glyphicon option, or a fontAwesome option; none found',
                    __METHOD__
                ));
            }

            if (null !== ($oTranslator = $this->getTranslator())) {
                $sButtonContent = $oTranslator->translate(
                    $sButtonContent,
                    $this->getTranslatorTextDomain()
                );
            }
        }

        if (!$oElement instanceof LabelAwareInterface || !$oElement->getLabelOption('disable_html_escape')) {
            $oEscapeHtmlHelper = $this->getEscapeHtmlHelper();
            $sButtonContent = $oEscapeHtmlHelper($sButtonContent);
        }

        /*
         * Manage icon
         */
        if ($aIconOptions) {
            if (is_scalar($aIconOptions)) {
                $aIconOptions = array (
                    'icon' => $aIconOptions,
                    'position' => self::ICON_PREPEND
                );
            }

            if (!is_array($aIconOptions)) {
                throw new LogicException(sprintf(
                    '"glyphicon" and "fontAwesome" button option expects a scalar value or an array, "%s" given',
                    is_object($aIconOptions) ? get_class($aIconOptions) : gettype($aIconOptions)
                ));
            }

            $position = 'prepend';

            if (!empty($aIconOptions['position'])) {
                $position = $aIconOptions['position'];
            }

            if (!empty($aIconOptions['icon'])) {
                $icon = $aIconOptions['icon'];
            }

            if (!is_scalar($icon)) {
                throw new LogicException(sprintf(
                    'Glyphicon and fontAwesome "icon" option expects a scalar value, "%s" given',
                    is_object($icon) ? get_class($icon) : gettype($icon)
                ));
            } elseif (!is_string($position)) {
                throw new LogicException(sprintf(
                    'Glyphicon and fontAwesome "position" option expects a string, "%s" given',
                    is_object($position) ? get_class($position) : gettype($position)
                ));
            } elseif ($position !== self::ICON_PREPEND && $position !== self::ICON_APPEND) {
                throw new LogicException(sprintf(
                    'Glyphicon and fontAwesome "position" option allows "'.self::ICON_PREPEND.'" or "'.self::ICON_APPEND.'", "%s" given',
                    is_object($position) ? get_class($position) : gettype($position)
                ));
            }

            if ($sButtonContent) {
                if ($position === self::ICON_PREPEND) {
                    $sButtonContent = $this->getView()->{$sIconHelperMethod}(
                        $icon,
                        isset($aIconOptions['attributes'])?$aIconOptions['attributes']:null
                    ).' '.$sButtonContent;
                } else {
                    $sButtonContent .= ' ' . $this->getView()->{$sIconHelperMethod}(
                        $icon,
                        isset($aIconOptions['attributes'])?$aIconOptions['attributes']:null
                    );
                }
            } else {
                $sButtonContent = $this->getView()->{$sIconHelperMethod}(
                    $icon,
                    isset($aIconOptions['attributes']) ? $aIconOptions['attributes'] : null
                );
            }
        }

        /*
         * Dropdown button
         */
        if ($aDropdownOptions = $oElement->getOption('dropdown')) {
            if (!is_array($aDropdownOptions)) {
                throw new LogicException(sprintf(
                    '"dropdown" option expects an array, "%s" given',
                    is_object($aDropdownOptions) ? get_class($aDropdownOptions) : gettype($aDropdownOptions)
                ));
            }

            if (empty($aDropdownOptions['split'])) {
                /*
                 * Class
                 */
                if (!preg_match('/(\s|^)dropdown-toggle(\s|$)/', $sClass = $oElement->getAttribute('class'))) {
                    $oElement->setAttribute('class', trim($sClass . ' dropdown-toggle'));
                }

                /*
                 * data-toggle
                 */
                $oElement->setAttribute('data-toggle', 'dropdown');
                $sMarkup = $this->openTag($oElement) .
                    sprintf(static::$dropdownToggleFormat, $sButtonContent) .
                    $this->closeTag();
            } else {
                /*
                 * Add caret element
                 */
                $sMarkup = $this->openTag($oElement) .
                    $sButtonContent .
                    $this->closeTag() .
                    sprintf(static::$dropdownCaretFormat, $oElement->getAttribute('class'));
            }

            /*
             * No container
             */
            if ($oElement->getOption('disable-twb')) {
                return $sMarkup . $this->getView()->dropdown()->renderListItems($aDropdownOptions);
            }

            /*
             * Render button + dropdown
             */
            return sprintf(
                static::$dropdownContainerFormat,
                //Drop way
                empty($aDropdownOptions['dropup']) ? '' : 'dropup',
                $sMarkup .
                $this->getView()->dropdown()->renderListItems($aDropdownOptions)
            );
        }

        return $this->openTag($oElement) . $sButtonContent . $this->closeTag();
    }
}
