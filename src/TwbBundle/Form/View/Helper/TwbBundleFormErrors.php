<?php

namespace TwbBundle\Form\View\Helper;

class TwbBundleFormErrors extends \Zend\Form\View\Helper\AbstractHelper {

    protected $defaultErrorText = 'There were errors in the form submission';
    protected $messageOpenFormat = '<h4>%s</h4><ul><li>';
    protected $messageCloseString = '</li></ul>';
    protected $messageSeparatorString = '</li><li>';

    /**
     * Invoke as function
     * @param \Zend\Form\FormInterface $oForm
     * @param string $sMessage
     * @param string $bDismissable
     * @return string|null
     */
    public function __invoke(\Zend\Form\FormInterface $oForm = null, $sMessage = null, $bDismissable = false) {
        if (!$oForm) {
            return $this;
        }

        if (!$sMessage) {
            $sMessage = $this->defaultErrorText;
        }

        if ($oForm->hasValidated() && !$oForm->isValid()) {

            return $this->render($oForm, $sMessage, $bDismissable);
        }

        return null;
    }

    /**
     * Renders the error messages.
     * @param \Zend\Form\FormInterface $oForm
     * @return string
     */
    public function render(\Zend\Form\FormInterface $oForm, $sMessage, $bDismissable = false) {
        $errorHtml = sprintf($this->messageOpenFormat, $sMessage);

        $sMessagesArray = array();

        foreach ($oForm->getMessages() as $fieldName => $sMessages) {
            foreach ($sMessages as $sMessage) {
                if ($oForm->get($fieldName)->getAttribute('id')) {
                    $sMessagesArray[] = sprintf(
                            '<a href="#%s">%s</a>', $oForm->get($fieldName)->getAttribute('id'), $oForm->get($fieldName)->getLabel() . ': ' . $sMessage
                    );
                } else {
                    $sMessagesArray[] = $oForm->get($fieldName)->getLabel() . ': ' . $sMessage;
                }
            }
        }

        return $this->dangerAlert($errorHtml . implode($this->messageSeparatorString, $sMessagesArray) . $this->messageCloseString, $bDismissable);
    }

    /**
     * Creates and returns a "danger" alert.
     * @param string  $content
     * @param boolean $bDismissable
     * @return string
     */
    public function dangerAlert($content, $bDismissable = false) {
        return $this->getView()->alert($content, array('class' => 'alert-danger'), $bDismissable);
    }

}
