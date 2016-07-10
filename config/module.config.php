<?php

return array(
    'twbbundle' => array (
        'ignoredViewHelpers' => array (
            'file',
            'checkbox',
            'radio',
            'submit',
            'multi_checkbox',
            'static',
            'button',
            'reset'
        ),
        'type_map' => array(),
        'class_map' => array(),
    ),
    'service_manager' => array (
        'factories' => array (
            'TwbBundle\Options\ModuleOptions' => 'TwbBundle\Options\Factory\ModuleOptionsFactory'
        )
    ),
    'view_helpers' => array (
        'invokables' => array (
            //Alert
            'alert' => 'TwbBundle\View\Helper\TwbBundleAlert',
            //Badge
            'badge' => 'TwbBundle\View\Helper\TwbBundleBadge',
            //Button group
            'buttonGroup' => 'TwbBundle\View\Helper\TwbBundleButtonGroup',
            //DropDown
            'dropDown' => 'TwbBundle\View\Helper\TwbBundleDropDown',
            //Form
            'form' => 'TwbBundle\Form\View\Helper\TwbBundleForm',
            'formButton' => 'TwbBundle\Form\View\Helper\TwbBundleFormButton',
            'formSubmit' => 'TwbBundle\Form\View\Helper\TwbBundleFormButton',
            'formCheckbox' => 'TwbBundle\Form\View\Helper\TwbBundleFormCheckbox',
            'formCollection' => 'TwbBundle\Form\View\Helper\TwbBundleFormCollection',
            'formElementErrors' => 'TwbBundle\Form\View\Helper\TwbBundleFormElementErrors',
            'formMultiCheckbox' => 'TwbBundle\Form\View\Helper\TwbBundleFormMultiCheckbox',
            'formRadio' => 'TwbBundle\Form\View\Helper\TwbBundleFormRadio',
            'formRow' => 'TwbBundle\Form\View\Helper\TwbBundleFormRow',
            'formStatic' => 'TwbBundle\Form\View\Helper\TwbBundleFormStatic',
            //Form Errors
            'formErrors' => 'TwbBundle\Form\View\Helper\TwbBundleFormErrors',
            //Glyphicon
            'glyphicon' => 'TwbBundle\View\Helper\TwbBundleGlyphicon',
            //FontAwesome
            'fontAwesome' => 'TwbBundle\View\Helper\TwbBundleFontAwesome',
            //Label
            'label' => 'TwbBundle\View\Helper\TwbBundleLabel',

            // ZF3
            'form_button' => 'TwbBundle\Form\View\Helper\TwbBundleFormButton',
            'form_submit' => 'TwbBundle\Form\View\Helper\TwbBundleFormButton',
            'form_checkbox' => 'TwbBundle\Form\View\Helper\TwbBundleFormCheckbox',
            'form_collection' => 'TwbBundle\Form\View\Helper\TwbBundleFormCollection',
            'form_element_errors' => 'TwbBundle\Form\View\Helper\TwbBundleFormElementErrors',
            'form_multi_checkbox' => 'TwbBundle\Form\View\Helper\TwbBundleFormMultiCheckbox',
            'form_radio' => 'TwbBundle\Form\View\Helper\TwbBundleFormRadio',
            'form_row' => 'TwbBundle\Form\View\Helper\TwbBundleFormRow',
            'form_static' => 'TwbBundle\Form\View\Helper\TwbBundleFormStatic',
            'form_errors' => 'TwbBundle\Form\View\Helper\TwbBundleFormErrors',

            'formbutton' => 'TwbBundle\Form\View\Helper\TwbBundleFormButton',
            'formsubmit' => 'TwbBundle\Form\View\Helper\TwbBundleFormButton',
            'formcheckbox' => 'TwbBundle\Form\View\Helper\TwbBundleFormCheckbox',
            'formcollection' => 'TwbBundle\Form\View\Helper\TwbBundleFormCollection',
            'formelement_errors' => 'TwbBundle\Form\View\Helper\TwbBundleFormElementErrors',
            'formmulti_checkbox' => 'TwbBundle\Form\View\Helper\TwbBundleFormMultiCheckbox',
            'formradio' => 'TwbBundle\Form\View\Helper\TwbBundleFormRadio',
            'formrow' => 'TwbBundle\Form\View\Helper\TwbBundleFormRow',
            'formstatic' => 'TwbBundle\Form\View\Helper\TwbBundleFormStatic',
            'formerrors' => 'TwbBundle\Form\View\Helper\TwbBundleFormErrors',
        ),
        'factories' => array (
            'formElement' => 'TwbBundle\Form\View\Helper\Factory\TwbBundleFormElementFactory',
            'form_element' => 'TwbBundle\Form\View\Helper\Factory\TwbBundleFormElementFactory',
            'formelement' => 'TwbBundle\Form\View\Helper\Factory\TwbBundleFormElementFactory',
        )
    ),
);
