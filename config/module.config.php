<?php

return [
    'twbbundle' => [
        'ignoredViewHelpers' => [
            'file',
            'checkbox',
            'radio',
            'submit',
            'multi_checkbox',
            'static',
            'button',
            'reset'
        ]
    ],
    'service_manager' => [
        'invokables' => [
            'twb_nav_view_helper_configurator'  => 'TwbBundle\View\Helper\Navigation\PluginConfigurator',
        ],
        'factories' => [
            'TwbBundle\Options\ModuleOptions' => 'TwbBundle\Options\Factory\ModuleOptionsFactory'
        ]
    ],
    'view_helpers' => [
        'invokables' => [
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
            'label' => 'TwbBundle\View\Helper\TwbBundleLabel'
        ],
        'factories' => [
            'formElement' => 'TwbBundle\Form\View\Helper\Factory\TwbBundleFormElementFactory',
        ]
    ],
];
