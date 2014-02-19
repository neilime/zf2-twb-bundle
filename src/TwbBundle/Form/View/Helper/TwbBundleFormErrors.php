<?php
namespace TwbBundle\Form\View\Helper;

use Zend\Form\View\Helper\AbstractHelper as ZendFormViewHelperAbstractHelper;
use Zend\Form\FieldsetInterface as ZendFormFieldsetInterface;
use Zend\Form\FormInterface as ZendFormFormInterface;

class TwbBundleFormErrors extends ZendFormViewHelperAbstractHelper
{
    protected $defaultErrorText = 'There were errors in the form submission';
    protected $messageOpenFormat = '<h4>%s</h4><ul><li>';
    protected $messageCloseString = '</li></ul>';
    protected $messageSeparatorString = '</li><li>';

    /**
     * Invoke as function
     *
     * @param ZendFormFormInterface $form
     * @param string $message
     * @param string $dismissable
     */
    public function __invoke(ZendFormFormInterface $form = null, $message = null, $dismissable = false)
    {
        if (!$form) {
            return $this;
        }

        if (!$message) {
            $message = $this->defaultErrorText;
        }

        if ($form->hasValidated() && !$form->isValid()) {

            return $this->render($form, $message, $dismissable);
        }

        return null;
    }

    /**
     * Renders the error messages.
     *
     * @param ZendFormFormInterface $form
     *
     * return string
     */
    public function render(ZendFormFormInterface $form, $message, $dismissable = false)
    {
        $errorHtml = sprintf($this->messageOpenFormat, $message);

        $messagesArray = array();

        foreach ($form->getMessages() as $fieldName => $messages) {
            error_log(get_class($form->get($fieldName)));
            foreach ($messages as $validatorName => $message) {
                if ($form->get($fieldName)->getAttribute('id')) {
                    $messagesArray[] = sprintf(
                        '<a href="#%s">%s</a>',
                        $form->get($fieldName)->getAttribute('id'),
                        $form->get($fieldName)->getLabel() . ': ' . $message
                    );
                } else {
                    $messagesArray[] = $form->get($fieldName)->getLabel() . ': ' . $message;
                }
            }
        }

        $messageString = implode($this->messageSeparatorString, $messagesArray);

        $errorHtml = $errorHtml . $messageString . $this->messageCloseString;

        $errorHtml = $this->dangerAlert($errorHtml, $dismissable);

        return $errorHtml;
    }

    /**
     * Creates and returns a "danger" alert.
     *
     * @param string  $content
     * @param boolean $dismissable
     *
     * return string
     */
    public function dangerAlert($content, $dismissable = false)
    {
        return $this->getView()->alert($content, array('class' => 'alert-danger'), $dismissable);
    }
}
