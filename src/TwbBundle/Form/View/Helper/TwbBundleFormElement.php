<?php

namespace TwbBundle\Form\View\Helper;

class TwbBundleFormElement extends \Zend\Form\View\Helper\FormElement implements \Zend\I18n\Translator\TranslatorAwareInterface {

    /**
     * @var string
     */
    protected static $addonFormat = '<%s class="%s">%s</%s>';

    /**
     * @var string
     */
    protected static $inputGroupFormat = '<div class="input-group %s">%s</div>';

    /**
     * Translator (optional)
     * @var \Zend\I18n\Translator\Translator
     */
    protected $translator;

    /**
     * Translator text domain (optional)
     * @var string
     */
    protected $translatorTextDomain = 'default';

    /**
     * Whether translator should be used
     * @var boolean
     */
    protected $translatorEnabled = true;

    /**
     * Instance map to view helper
     *
     * @var array
     */
    protected $classMap = array(
        'Zend\Form\Element\Button' => 'formbutton',
        'Zend\Form\Element\Captcha' => 'formcaptcha',
        'Zend\Form\Element\Csrf' => 'formhidden',
        'Zend\Form\Element\Collection' => 'formcollection',
        'Zend\Form\Element\DateTimeSelect' => 'formdatetimeselect',
        'Zend\Form\Element\DateSelect' => 'formdateselect',
        'Zend\Form\Element\MonthSelect' => 'formmonthselect',
        'TwbBundle\Form\Element\StaticElement' => 'formStatic',
    );

    /**
     * Render an element
     * @param \Zend\Form\ElementInterface $oElement
     * @return string
     */
    public function render(\Zend\Form\ElementInterface $oElement) {
        // Add form-controll class
        $sElementType = $oElement->getAttribute('type');
        if (
                !in_array($sElementType, array('file', 'checkbox', 'radio', 'submit', 'multi_checkbox', 'static', 'button', 'reset')) && !($oElement instanceof \Zend\Form\Element\Collection)
        ) {
            if ($sElementClass = $oElement->getAttribute('class')) {
                if (!preg_match('/(\s|^)form-control(\s|$)/', $sElementClass)) {
                    $oElement->setAttribute('class', trim($sElementClass . ' form-control'));
                }
            } else {
                $oElement->setAttribute('class', 'form-control');
            }
        }

        $sMarkup = parent::render($oElement);

        // Addon prepend
        if ($aAddOnPrepend = $oElement->getOption('add-on-prepend')) {
            $sMarkup = $this->renderAddOn($aAddOnPrepend) . $sMarkup;
        }

        // Addon append
        if ($aAddOnAppend = $oElement->getOption('add-on-append')) {
            $sMarkup .= $this->renderAddOn($aAddOnAppend);
        }

        if ($aAddOnAppend || $aAddOnPrepend) {
            $sSpecialClass = '';
            // Input size
            if ($sElementClass = $oElement->getAttribute('class')) {
                if (preg_match('/(\s|^)input-lg(\s|$)/', $sElementClass)) {
                    $sSpecialClass .= ' input-group-lg';
                } elseif (preg_match('/(\s|^)input-sm(\s|$)/', $sElementClass)) {
                    $sSpecialClass .= ' input-group-sm';
                }
            }
            return sprintf(
                    self::$inputGroupFormat, trim($sSpecialClass), $sMarkup
            );
        }
        return $sMarkup;
    }

    /**
     * Render addo-on markup
     * @param string $aAddOnOptions
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @return string
     */
    protected function renderAddOn($aAddOnOptions) {
        if (empty($aAddOnOptions)) {
            throw new \InvalidArgumentException('Addon options are empty');
        }
        if ($aAddOnOptions instanceof \Zend\Form\ElementInterface) {
            $aAddOnOptions = array('element' => $aAddOnOptions);
        } elseif (is_scalar($aAddOnOptions)) {
            $aAddOnOptions = array('text' => $aAddOnOptions);
        } elseif (!is_array($aAddOnOptions)) {
            throw new \InvalidArgumentException('Addon options expects an array or a scalar value, "' . gettype($aAddOnOptions) . '" given');
        }

        $sMarkup = '';
        $sAddonTagName = 'span';
        $sAddonClass = '';
        if (!empty($aAddOnOptions['text'])) {
            if (!is_scalar($aAddOnOptions['text'])) {
                throw new \LogicException('"text" option expects a scalar value, "' . gettype($aAddOnOptions['text']) . '" given');
            } elseif (($oTranslator = $this->getTranslator())) {
                $sMarkup .= $oTranslator->translate($aAddOnOptions['text'], $this->getTranslatorTextDomain());
            } else {
                $sMarkup .= $aAddOnOptions['text'];
            }
            $sAddonClass .= ' input-group-addon';
        } elseif (!empty($aAddOnOptions['element'])) {
            if (
                    is_array($aAddOnOptions['element']) || ($aAddOnOptions['element'] instanceof \Traversable && !($aAddOnOptions['element'] instanceof \Zend\Form\ElementInterface))
            ) {
                $oFactory = new \Zend\Form\Factory();
                $aAddOnOptions['element'] = $oFactory->create($aAddOnOptions['element']);
            } elseif (!($aAddOnOptions['element'] instanceof \Zend\Form\ElementInterface)) {
                throw new \LogicException(sprintf(
                        '"element" option expects an instanceof \Zend\Form\ElementInterface, "%s" given', is_object($aAddOnOptions['element']) ? get_class($aAddOnOptions['element']) : gettype($aAddOnOptions['element'])
                ));
            }
            $aAddOnOptions['element']->setOptions(array_merge($aAddOnOptions['element']->getOptions(), array('disable-twb' => true)));
            $sMarkup .= $this->render($aAddOnOptions['element']);

            if ($aAddOnOptions['element'] instanceof \Zend\Form\Element\Button) {
                $sAddonClass .= ' input-group-btn';
                //Element contains dropdown, so add-on container must be a "div"
                if ($aAddOnOptions['element']->getOption('dropdown')) {
                    $sAddonTagName = 'div';
                }
            } else {
                $sAddonClass .= ' input-group-addon';
            }
        }

        return sprintf(self::$addonFormat, $sAddonTagName, trim($sAddonClass), $sMarkup, $sAddonTagName);
    }

    /**
     * Sets translator to use in helper
     * @see \Zend\I18n\Translator\TranslatorAwareInterface::setTranslator()
     * @param \Zend\I18n\Translator\TranslatorInterface $oTranslator : [optional] translator. Default is null, which sets no translator.
     * @param string $sTextDomain : [optional] text domain Default is null, which skips setTranslatorTextDomain
     * @return \TwbBundle\Form\View\Helper\TwbBundleFormElement
     */
    public function setTranslator(\Zend\I18n\Translator\TranslatorInterface $oTranslator = null, $sTextDomain = null) {
        $this->translator = $oTranslator;
        if (null !== $sTextDomain) {
            $this->setTranslatorTextDomain($sTextDomain);
        }
        return $this;
    }

    /**
     * Returns translator used in helper
     * @see \Zend\I18n\Translator\TranslatorAwareInterface::getTranslator()
     * @return null|\Zend\I18n\Translator\TranslatorInterface
     */
    public function getTranslator() {
        return $this->isTranslatorEnabled() ? $this->translator : null;
    }

    /**
     * Checks if the helper has a translator
     * @see \Zend\I18n\Translator\TranslatorAwareInterface::hasTranslator()
     * @return boolean
     */
    public function hasTranslator() {
        return !!$this->getTranslator();
    }

    /**
     * Sets whether translator is enabled and should be used
     * @see \Zend\I18n\Translator\TranslatorAwareInterface::setTranslatorEnabled()
     * @param boolean $bEnabled
     * @return \TwbBundle\Form\View\Helper\TwbBundleFormElement
     */
    public function setTranslatorEnabled($bEnabled = true) {
        $this->translatorEnabled = !!$bEnabled;
        return $this;
    }

    /**
     * Returns whether translator is enabled and should be used
     * @see \Zend\I18n\Translator\TranslatorAwareInterface::isTranslatorEnabled()
     * @return boolean
     */
    public function isTranslatorEnabled() {
        return $this->translatorEnabled;
    }

    /**
     * Set translation text domain
     * @see \Zend\I18n\Translator\TranslatorAwareInterface::setTranslatorTextDomain()
     * @param string $sTextDomain
     * @return \TwbBundle\Form\View\Helper\TwbBundleFormElement
     */
    public function setTranslatorTextDomain($sTextDomain = 'default') {
        $this->translatorTextDomain = $sTextDomain;
        return $this;
    }

    /**
     * Return the translation text domain
     * @see \Zend\I18n\Translator\TranslatorAwareInterface::getTranslatorTextDomain()
     * @return string
     */
    public function getTranslatorTextDomain() {
        return $this->translatorTextDomain;
    }

}
