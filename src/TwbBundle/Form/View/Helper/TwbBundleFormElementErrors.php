<?php
namespace TwbBundle\Form\View\Helper;
class TwbBundleFormElementErrors extends \Zend\Form\View\Helper\FormElementErrors{
	protected $messageOpenFormat = '<ul class="help-block"><li>';
	protected $messageCloseString = '</li></ul>';
	protected $messageSeparatorString = '</li><li>';
}