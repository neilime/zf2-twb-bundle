<?php
namespace TwbBundle\Form\View\Helper;

use Zend\Form\ElementInterface;

class TwbBundleFormStatic extends \Zend\Form\View\Helper\AbstractHelper{
	/**
	 * @var string
	 */
	private static $staticFormat = '<p class="form-control-static">%s</p>';

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
	 * @see \Zend\Form\View\Helper\AbstractHelper::render()
	 * @param ElementInterface $oElement
	 * @return string
	 */
	public function render(ElementInterface $oElement){
		return sprintf(self::$staticFormat,$oElement->getValue());
	}
}