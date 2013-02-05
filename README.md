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

Installation
------------

### Main Setup

#### By cloning project

1. Install the .
2. Clone this project into your `./vendor/` directory.

#### With composer

1. Add this project in your composer.json:

    ```json
    "require": {
        "neilime/zf2-twb-bundle": "dev-master"
    }
    ```

2. Now tell composer to download TwbBundle by running the command:

    ```bash
    $ php composer.phar update
    ```

#### Post installation

1. Enabling it in your `application.config.php`file.

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
    
# How to use TwbBundle

...