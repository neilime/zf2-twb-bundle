<?php
namespace TwbBundle\Form\View\Helper;

use Laminas\Form\ElementInterface;
use Laminas\View\Helper\AbstractHelper;

class TwbBundleFormStatic extends AbstractHelper
{
    /**
     * @var string
     */
    protected static $staticFormat = '<p class="form-control-static">%s</p>';

    /**
     * Invoke helper as functor
     *
     * Proxies to {@link render()}.
     *
     * @param  ElementInterface|null $element
     * @return string|TwbBundleFormStatic
     */
    public function __invoke(ElementInterface $element = null)
    {
        if (!$element) {
            return $this;
        }

        return $this->render($element);
    }

    /**
     * @see \Laminas\Form\View\Helper\AbstractHelper::render()
     * @param ElementInterface $oElement
     * @return string
     */
    public function render(ElementInterface $oElement)
    {
        return sprintf(static::$staticFormat, $oElement->getValue());
    }
}
