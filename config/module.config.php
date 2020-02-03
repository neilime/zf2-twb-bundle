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
            'TwbBundle\Options\ModuleOptions' => 'TwbBundle\Options\Factory\ModuleOptionsFactory',
        ),
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
            'dropdown' => 'TwbBundle\View\Helper\TwbBundleDropDown',
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
            'form_alert' => 'TwbBundle\View\Helper\TwbBundleAlert',
            'form_badge' => 'TwbBundle\View\Helper\TwbBundleBadge',
            'form_buttonGroup' => 'TwbBundle\View\Helper\TwbBundleButtonGroup',
            'form_dropDown' => 'TwbBundle\View\Helper\TwbBundleDropDown',
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
            'form_glyphicon' => 'TwbBundle\View\Helper\TwbBundleGlyphicon',
            'form_fontAwesome' => 'TwbBundle\View\Helper\TwbBundleFontAwesome',
            'formalert' => 'TwbBundle\View\Helper\TwbBundleAlert',
            'formbadge' => 'TwbBundle\View\Helper\TwbBundleBadge',
            'formbuttonGroup' => 'TwbBundle\View\Helper\TwbBundleButtonGroup',
            'formdropDown' => 'TwbBundle\View\Helper\TwbBundleDropDown',
            'formbutton' => 'TwbBundle\Form\View\Helper\TwbBundleFormButton',
            'formsubmit' => 'TwbBundle\Form\View\Helper\TwbBundleFormButton',
            'formcheckbox' => 'TwbBundle\Form\View\Helper\TwbBundleFormCheckbox',
            'formcollection' => 'TwbBundle\Form\View\Helper\TwbBundleFormCollection',
            'formelement_errors' => 'TwbBundle\Form\View\Helper\TwbBundleFormElementErrors',
            'formmulticheckbox' => 'TwbBundle\Form\View\Helper\TwbBundleFormMultiCheckbox',
            'formradio' => 'TwbBundle\Form\View\Helper\TwbBundleFormRadio',
            'formrow' => 'TwbBundle\Form\View\Helper\TwbBundleFormRow',
            'formstatic' => 'TwbBundle\Form\View\Helper\TwbBundleFormStatic',
            'formerrors' => 'TwbBundle\Form\View\Helper\TwbBundleFormErrors',
            'formglyphicon' => 'TwbBundle\View\Helper\TwbBundleGlyphicon',
            'formfontAwesome' => 'TwbBundle\View\Helper\TwbBundleFontAwesome',

            // zend
            'form_label' => 'Laminas\Form\View\Helper\FormLabel',
            'formlabel' => 'Laminas\Form\View\Helper\FormLabel',
            'formemail'    => 'Laminas\Form\View\Helper\FormEmail',
            'formpassword' => 'Laminas\Form\View\Helper\FormPassword',
            'formfile'     => 'Laminas\Form\View\Helper\FormFile',
            'formtext'     => 'Laminas\Form\View\Helper\FormText',
            'formtextarea' => 'Laminas\Form\View\Helper\FormTextarea',
            'formselect'   => 'Laminas\Form\View\Helper\FormSelect',
            'forminput'    => 'Laminas\Form\View\Helper\FormInput',
            'formhidden'   => 'Laminas\Form\View\Helper\FormHidden',
        ),
        'factories' => array (
            'formElement' => 'TwbBundle\Form\View\Helper\Factory\TwbBundleFormElementFactory',
            'form_element' => 'TwbBundle\Form\View\Helper\Factory\TwbBundleFormElementFactory',
            'formelement' => 'TwbBundle\Form\View\Helper\Factory\TwbBundleFormElementFactory',
            'TwbBundle\Form\View\Helper\TwbBundleFormElement' => 'TwbBundle\Form\View\Helper\Factory\TwbBundleFormElementFactory',
            'laminasviewhelpernavigation'  => 'TwbBundle\Navigation\View\NavigationHelperFactory',
        ),
        'aliases' => array (
            'form_element' => 'TwbBundle\Form\View\Helper\TwbBundleFormElement',
            'navigation' => 'laminasviewhelpernavigation',
            'Navigation' => 'laminasviewhelpernavigation',
        ),
    ),
);
