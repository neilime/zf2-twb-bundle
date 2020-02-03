<?php

namespace TwbBundle\View\Helper;

use Laminas\Form\View\Helper\AbstractHelper;
use InvalidArgumentException;

class TwbBundleBadge extends AbstractHelper
{
    /**
     * @var string
     */
    protected static $badgeFormat = '<span %s>%s</span>';

    /**
     * Invoke helper as functor, proxies to {@link render()}.
     * @param string $sBadgeMessage
     * @param array $aBadgeAttributes : [optionnal]
     * @return string|TwbBundleBadge
     */
    public function __invoke($sBadgeMessage = null, array $aBadgeAttributes = null)
    {
        if ($sBadgeMessage === null) {
            return $this;
        }
        return $this->render($sBadgeMessage, $aBadgeAttributes);
    }

    /**
     * Retrieve badge markup
     * @param string $sBadgeMessage
     * @param  array $aBadgeAttributes : [optionnal]
     * @throws InvalidArgumentException
     * @return string
     */
    public function render($sBadgeMessage, array $aBadgeAttributes = null)
    {
        if (!is_scalar($sBadgeMessage)) {
            throw new InvalidArgumentException(sprintf(
                'Badge message expects a scalar value, "%s" given',
                is_object($sBadgeMessage) ? get_class($sBadgeMessage) : gettype($sBadgeMessage)
            ));
        }

        if (empty($aBadgeAttributes)) {
            $aBadgeAttributes = array('class' => 'badge');
        } else {
            if (empty($aBadgeAttributes['class'])) {
                $aBadgeAttributes['class'] = 'badge';
            } elseif (!preg_match('/(\s|^)badge(\s|$)/', $aBadgeAttributes['class'])) {
                $aBadgeAttributes['class'] .= ' badge';
            }
        }

        if (null !== ($oTranslator = $this->getTranslator())) {
            $sBadgeMessage = $oTranslator->translate($sBadgeMessage, $this->getTranslatorTextDomain());
        }

        return sprintf(
            static::$badgeFormat,
            $this->createAttributesString($aBadgeAttributes),
            $sBadgeMessage
        );
    }
}
