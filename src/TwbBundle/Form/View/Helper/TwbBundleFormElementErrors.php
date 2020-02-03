<?php
namespace TwbBundle\Form\View\Helper;

use Laminas\Form\View\Helper\FormElementErrors;

class TwbBundleFormElementErrors extends FormElementErrors
{
    protected $attributes = array(
        'class' => 'help-block'
    );
}
