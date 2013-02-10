TwbBundle
=====================

`Work in progress`

[![Build Status](https://travis-ci.org/neilime/zf2-twb-bundle.png?branch=master)](https://travis-ci.org/neilime/zf2-twb-bundle)

Created by Neilime

Introduction
------------

TwbBundle is a module for Zend Framework 2, for easy integration of the Twitter Bootstrap. 

P.S. Sorry for my english. If You wish to help me with this project or correct my english description - You are welcome :)

Requirements
------------

* [Zend Framework 2](https://github.com/zendframework/zf2) (latest master)
* [Twitter Bootstrap](https://github.com/twitter/bootstrap) (latest master)

Installation
------------

### Main Setup

#### By cloning project

1. Clone this project into your `./vendor/` directory.
2. (Optionnal) Clone the [Twitter bootstrap project](https://github.com/twitter/bootstrap) (latest master) into your `./vendor/` directory.

#### With composer

1. Add this project in your composer.json:

    ```json
    "require": {
        "neilime/zf2-twb-bundle": "dev-master"
    }
    ```

2. Now tell composer to download `TwbBundle` by running the command:

    ```bash
    $ php composer.phar update
    ```

#### Post installation

1. Enabling it in your `application.config.php` file.

    ```php
    <?php
    return array(
        'modules' => array(
            // ...
            'TwbBundle',
        ),
        // ...
    );
    ```

2. Include Twitter Bootstrap assets

###### With AssetsBundle module (easy way)
    
* Install the [AssetsBundle] module (https://github.com/neilime/zf2-assets-bundle)
* Edit the application module configuration file `module/Application/config/module.config.php`, adding the configuration fragment below:

```php
<?php
return array(
	//...
	'asset_bundle' => array(
    	'assets' => array(
			'less' => array('@zfRootPath/vendor/twitter/bootstrap/less/bootstrap.less')
    	)
    ),
    //...
);
```

* Edit layout file `module/Application/view/layout/layout.phtml`, echo head scripts :

```php
<?php
	//...	
		
	echo $this->headScript();
	
	//...
?>
```

###### Manually
    
* Copy `vendor/twitter/bootstrap/docs/assets/css/bootstrap.css` file into your asset folder and add it in your head scripts
    
# How to use TwbBundle

## Simple examples

* Render a dropdown button

```php
 <?php
	//...
	//Create button
	$button = new \Zend\Element\Button('test-button',array(
		'label' => 'Action',
		'dropdown' => array('actions' => array(
			'Action',
			'Another action',
			'Something else here',
			'-',
			'Separated link'
		))
	)));
	//Render it in your view
	echo $this->formButton($button);
	//...
?>
```

* Render a search form

```php
 <?php
	//...
	//Create form
	$form = new \Zend\Form\Form();
	$form->add(array(
		'name' => 'input-search-append',
		'attributes' => array(
			'class' => 'search-query input-medium'
		),
		'options' => array('twb' => array(
			'append' => array(
				'type' => 'buttons',
				'buttons' => array(
					'search-submit-append' => array(
						'options' => array('label' => 'Search'),
						'attributes' => array('type' => 'submit')
					)
				)
			)
		))
	))->add(array(
		'name' => 'input-search-prepend',
		'attributes' => array(
			'class' => 'search-query input-medium'
		),
		'options' => array('twb' => array(
			'prepend' => array(
				'type' => 'buttons',
				'buttons' => array(
					'search-submit-prepend' => array(
						'options' => array('label' => 'Search'),
						'attributes' => array('type' => 'submit')
					)
				)
			)
		))
	));
	//Render it in your view
	$this->form($form,\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_SEARCH);
	//...
?>
```

## Features

TwbBundle is abble to render [Twitter bootstrap demo site](http://twitter.github.com/bootstrap) forms, inputs, buttons & navigation stuff. (tests are written to check that)

### Forms

Render \Zend\Form\FormInterface

#### Form layout :

Layout us define when calling form view helper

```php
<?php

$this->form($form,$layout);

?>
```

* None : `null`
* Search form : `\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_SEARCH`
* Inline form : `\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_INLINE`
* Horizontal form (default) : `\TwbBundle\Form\View\Helper\TwbBundleForm::LAYOUT_HORIZONTAL`

### Inputs

Render \Zend\Form\Element\ElementInterface

All elements options are defined in `twb` array

```php
<?php

new \Zend\Form\Element\Element('test-element',array(
	'twb' => array(
		/** TwbBundle options **/
	)
);

?>
```

#### Appended and / or prepended

For all prepended / appended types : 

```php
<?php



new \Zend\Form\Element\Element('test-element',array(
	'twb' => array(
		'prepend' => array(
			'type' => 'prepended type',
			//Prepended type option
		),
		'append' => array(
			'type' => 'appended type',
			//Appended type option
		)
	)
);
?>
```


* Text :

Appended / prepended texts are translated 

```php
<?php

//Prepended text

new \Zend\Form\Element\Element('test-element',array(
	'twb' => array(
		'prepend' => array(
			'type' => 'text',
			'text' => 'Prepended text'
		)
	)
);
?>
```

* Icon : 

```php
<?php

//Appended icon

new \Zend\Form\Element\Element('test-element',array(
	'twb' => array(
		'append' => array(
			'type' => 'icon',
			'icon' => 'enveloppe' //icon class excluding "icon-"
		)
	)
);
?>
```

* Button(s) :

Button options are explained [below](#buttons)
 
```php
<?php

//Appended buttons

new \Zend\Form\Element\Element('test-element',array(
	'twb' => array(
		'append' => array(
			'type' => 'icon',
			'buttons' => array(
				'button-one' => array(/* Button factory options, name is not mandatory if given with the array key */),
				new \Zend\Form\Element\Button('button-two',array(/* Button options */))
			)
		)
	)
);
?>
```

* Or what ever you want :

```php
<?php

//Appended markup

new \Zend\Form\Element\Element('test-element',array(
	'twb' => array(
		'append' => '<span>Simple appended text</span>'
	)
);
?>
```

#### Form actions

This option allows an element to be in form actions part

```php
<?php

//Element in form actions

new \Zend\Form\Element\Element('test-element',array(
	'twb' => array(
		'formAction' => true
	)
);
?>
```

#### Help :

* Inline
```php
<?php
new \Zend\Form\Element\Element('test-element',array(
	'twb' => array(
		'help-inline' => 'Inline help text'
	)
);
?>
```

* Block
```php
<?php
new \Zend\Form\Element\Element('test-element',array(
	'twb' => array(
		'help-block' => 'A longer block of help text that breaks onto a new line and may extend beyond one line.'
	)
);
?>
```

#### Validation states

Validations are only rendered with horizontal form layout

```php

//Element with "warning" state

<?php
new \Zend\Form\Element\Element('test-element',array(
	'twb' => array(
		'state' => 'warning'
	)
);
?>
```

### Buttons

Render \Zend\Form\Element\Button

#### Icons
#### Button dropdowns
#### Split button dropdowns
#### Dropup menus

### Navigation

`Work in progress`