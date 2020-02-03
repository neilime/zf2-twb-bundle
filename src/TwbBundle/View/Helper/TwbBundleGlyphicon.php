<?php

namespace TwbBundle\View\Helper;

use InvalidArgumentException;
use Laminas\Form\View\Helper\AbstractHelper;

class TwbBundleGlyphicon extends AbstractHelper
{
    /**
     * @var string
     */
    protected static $glyphiconFormat = '<span %s></span>';

    /**
     * Invoke helper as functor, proxies to {@link render()}.
     * @param string $sGlyphicon
     * @param array $aGlyphiconAttributes : [optionnal]
     * @return string|TwbBundleGlyphicon
     */
    public function __invoke($sGlyphicon = null, array $aGlyphiconAttributes = null)
    {
        if (!$sGlyphicon) {
            return $this;
        }
        return $this->render($sGlyphicon, $aGlyphiconAttributes);
    }

    /**
     * Retrieve glyphicon markup
     * @param string $sGlyphicon
     * @param  array $aGlyphiconAttributes : [optionnal]
     * @throws InvalidArgumentException
     * @return string
     */
    public function render($sGlyphicon, array $aGlyphiconAttributes = null)
    {
        if (!is_scalar($sGlyphicon)) {
            throw new InvalidArgumentException('Glyphicon expects a scalar value, "' . gettype($sGlyphicon) . '" given');
        }

        if (empty($aGlyphiconAttributes)) {
            $aGlyphiconAttributes = array('class' => 'glyphicon');
        } else {

            if (empty($aGlyphiconAttributes['class'])) {
                $aGlyphiconAttributes['class'] = 'glyphicon';
            } elseif (!preg_match('/(\s|^)glyphicon(\s|$)/', $aGlyphiconAttributes['class'])) {
                $aGlyphiconAttributes['class'] .= ' glyphicon';
            }
        }

        if (strpos('glyphicon-', $sGlyphicon) !== 0) {
            $sGlyphicon = 'glyphicon-' . $sGlyphicon;
        }

        if (!preg_match('/(\s|^)' . preg_quote($sGlyphicon, '/') . '(\s|$)/', $aGlyphiconAttributes['class'])) {
            $aGlyphiconAttributes['class'] .= ' ' . $sGlyphicon;
        }

        return sprintf(
            static::$glyphiconFormat,
            $this->createAttributesString($aGlyphiconAttributes)
        );
    }
}
