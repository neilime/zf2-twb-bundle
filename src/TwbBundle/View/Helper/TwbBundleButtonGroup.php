<?php

namespace TwbBundle\View\Helper;

class TwbBundleButtonGroup extends \Zend\Form\View\Helper\AbstractHelper {

    /**
     * @var string
     */
    protected static $buttonGroupContainerFormat = '<div %s>%s</div>';

    /**
     * @var string
     */
    protected static $buttonGroupJustifiedFormat = '<div class="btn-group">%s</div>';

    /**
     * @var \TwbBundle\Form\View\Helper\TwbBundleFormElement
     */
    protected $formElementHelper;

    /**
     * @param array $aButtons
     * @param array $aButtonGroupOptions
     * @return \TwbBundle\View\Helper\TwbBundleButtonGroup|string
     */
    public function __invoke(array $aButtons = null, array $aButtonGroupOptions = null) {
        return $aButtons ? $this->render($aButtons, $aButtonGroupOptions) : $this;
    }

    /**
     * Render button groups markup
     * @param array $aButtons
     * @param array $aButtonGroupOptions
     * @throws \LogicException
     * @return string
     */
    public function render(array $aButtons, array $aButtonGroupOptions = null) {
        // ### Button group container attributes ###
        if (empty($aButtonGroupOptions['attributes'])) {
            $aButtonGroupOptions['attributes'] = array('class' => 'btn-group');
        } else {
            if (!is_array($aButtonGroupOptions['attributes'])) {
                throw new \LogicException('"attributes" option expects an array, "' . gettype($aButtonGroupOptions['attributes']) . '" given');
            }
            if (empty($aButtonGroupOptions['attributes']['class'])) {
                $aButtonGroupOptions['attributes']['class'] = 'btn-group';
            } elseif (!preg_match('/(\s|^)(?:btn-group|btn-group-vertical)(\s|$)/', $aButtonGroupOptions['attributes']['class'])) {
                $aButtonGroupOptions['attributes']['class'] .= ' btn-group';
            }
        }

        // ### Render button group ###

        return sprintf(
                self::$buttonGroupContainerFormat,
                //Container attributes
                $this->createAttributesString($aButtonGroupOptions['attributes']),
                //Buttons
                $this->renderButtons($aButtons, strpos($aButtonGroupOptions['attributes']['class'], 'btn-group-justified') !== false)
        );
    }

    /**
     * Render buttons markup
     * @param array $aButtons
     * @return string
     */
    protected function renderButtons(array $aButtons, $bJustified = false) {
        $sMarkup = '';
        foreach ($aButtons as $oButton) {
            if (
                    is_array($oButton) || ($oButton instanceof \Traversable && !($oButton instanceof \Zend\Form\ElementInterface))
            ) {
                $oFactory = new \Zend\Form\Factory();
                $oButton = $oFactory->create($oButton);
            } elseif (!($oButton instanceof \Zend\Form\ElementInterface)) {
                throw new \LogicException(sprintf(
                        'Button expects an instanceof \Zend\Form\ElementInterface or an array / \Traversable, "%s" given', is_object($oButton) ? get_class($oButton) : gettype($oButton)
                ));
            }

            $sButtonMarkup = $this->getFormElementHelper()->__invoke($oButton);

            $sMarkup .= $bJustified ? sprintf(self::$buttonGroupJustifiedFormat, $sButtonMarkup) : $sButtonMarkup;
        }
        return $sMarkup;
    }

    /**
     * @return \TwbBundle\Form\View\Helper\TwbBundleFormElement
     */
    public function getFormElementHelper() {
        if ($this->formElementHelper instanceof \TwbBundle\Form\View\Helper\TwbBundleFormElement) {
            return $this->formElementHelper;
        }
        if (method_exists($this->view, 'plugin')) {
            return $this->formElementHelper = $this->view->plugin('form_element');
        }

        return $this->formElementHelper = new \TwbBundle\Form\View\Helper\TwbBundleFormElement();
    }

}
