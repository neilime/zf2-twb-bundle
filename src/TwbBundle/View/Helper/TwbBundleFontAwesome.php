<?php
/**
 * Project: zf2-twb-bundle, File: TwbBundleFontAwesome.php
 * @author Michael Schakulat <michael@fetchit.de>
 * @package zf2-twb-bundle
 */

namespace TwbBundle\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;

/**
 * Class TwbBundleFontAwesome
 * @package TwbBundle\View\Helper
 */
class TwbBundleFontAwesome extends AbstractHelper
{
    /**
     * @var string
     */
    private static $faFormat = '<span %s></span>';

    /**
     * Invoke helper as functor, proxies to {@link render()}.
     * @param string $sFontAwesome
     * @param array $aFontAwesomeAttributes : [optionnal]
     * @return string|\TwbBundle\View\Helper\TwbBundleFontAwesome
     */
    public function __invoke($sFontAwesome = null, array $aFontAwesomeAttributes = null)
    {
        if (!$sFontAwesome) {
            return $this;
        }
        return $this->render($sFontAwesome, $aFontAwesomeAttributes);
    }

    /**
     * Retrieve fontAwesome markup
     * @param string $sFontAwesome
     * @param  array $aFontAwesomeAttributes : [optionnal]
     * @throws \InvalidArgumentException
     * @return string
     */
    public function render($sFontAwesome, array $aFontAwesomeAttributes = null)
    {
        if (!is_scalar($sFontAwesome)) {
            throw new \InvalidArgumentException(
                sprintf(
                    'FontAwesome expects a scalar value, "%s" given',
                    gettype($sFontAwesome)
                )
            );
        }

        if (empty($aFontAwesomeAttributes)) {
            $aFontAwesomeAttributes = array('class' => 'fa');
        } else {

            if (empty($aFontAwesomeAttributes['class'])) {
                $aFontAwesomeAttributes['class'] = 'fa';
            } elseif (!preg_match('/(\s|^)fa(\s|$)/', $aFontAwesomeAttributes['class'])) {
                $aFontAwesomeAttributes['class'] .= ' fa';
            }
        }

        if (strpos('fa-', $sFontAwesome) !== 0) {
            $sFontAwesome = 'fa-' . $sFontAwesome;
        }

        if (!preg_match('/(\s|^)' . preg_quote($sFontAwesome, '/') . '(\s|$)/', $aFontAwesomeAttributes['class'])) {
            $aFontAwesomeAttributes['class'] .= ' ' . $sFontAwesome;
        }

        return sprintf(
            self::$faFormat,
            $this->createAttributesString($aFontAwesomeAttributes)
        );
    }
} 