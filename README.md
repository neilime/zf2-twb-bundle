TwbBundle
=====================

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

## Configuration

