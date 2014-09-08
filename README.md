TwbBundle, v2.0 
=====================
(Supports Twitter Bootstrap v3.*)

[![Build Status](https://travis-ci.org/neilime/zf2-twb-bundle.png?branch=master)](https://travis-ci.org/neilime/zf2-twb-bundle)
[![Latest Stable Version](https://poser.pugx.org/neilime/zf2-twb-bundle/v/stable.png)](https://packagist.org/packages/neilime/zf2-twb-bundle)
[![Total Downloads](https://poser.pugx.org/neilime/zf2-twb-bundle/downloads.png)](https://packagist.org/packages/neilime/zf2-twb-bundle)
[![Dependency Status](https://www.versioneye.com/user/projects/5294fa9d632bac5a67000046/badge.png)](https://www.versioneye.com/user/projects/5294fa9d632bac5a67000046)

NOTE : If you want to contribute don't hesitate, I'll review any PR.

<a href='https://pledgie.com/campaigns/26667'><img alt='Support the project' src='https://pledgie.com/campaigns/26667.png?skin_name=chrome' border='0' ><br/></a> 

Introduction
------------

__TwbBundle__ is a module for Zend Framework 2, for easy integration of the [Twitter Bootstrap v3.*](https://github.com/twbs/bootstrap).

Contributing
------------

If you wish to contribute to TwbBundle, please read the [CONTRIBUTING.md](CONTRIBUTING.md) file.

Demonstration / example
-----------------------

Render forms, buttons, alerts with TwbBundle : see it in action [on-line](http://neilime.github.io/zf2-twb-bundle/demo.html).


Requirements
------------

* [Zend Framework 2](https://github.com/zendframework/zf2) (2.*)
* [Twitter Bootstrap](https://github.com/twbs/bootstrap) (v3.*)

Installation
------------

### Main Setup

#### By cloning project (manual)

1. Clone this project into your `./vendor/` directory.
2. (Optionnal) Clone the [Twitter bootstrap project](https://github.com/twbs/bootstrap) (v3.*) into your `./vendor/` directory.

#### With composer (the faster way)

1. Add this project in your composer.json:

    ```json
    "require": {
        "neilime/zf2-twb-bundle": "2.*@stable"
    }
    ```

2. Now tell composer to download __TwbBundle__ by running the command:

    ```bash
    $ php composer.phar update
    ```

#### Post installation

1. Enabling it in your `application.config.php` file.

    ```php
    return array(
        'modules' => array(
            // ...
            'TwbBundle',
        ),
        // ...
    );
    ```

2. Include Twitter Bootstrap assets

###### With __AssetsBundle__ module (easy way)

* Install the [AssetsBundle module](https://github.com/neilime/zf2-twb-bundle/tree/1.0)(1.0)
* Install [Twitter Bootstrap](https://github.com/twbs/bootstrap/tree/v2.3.2) (v2.3.2)
* Edit the application module configuration file `module/Application/config/module.config.php`, adding the configuration fragment below:

    ```php
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

* Edit layout file `module/Application/view/layout/layout.phtml`, to render head scripts :

    ```php
    //...
    echo $this->headScript();
    //...
    ```

###### Manually

* Copy `bootstrap.css` file (available on Twitter Bootstrap website(https://github.com/twbs/bootstrap/archive/v3.0.0.zip) into your assets folder and add it in your head scripts

# How to use __TwbBundle__

## View helpers

__TwbBundle__ provides view helpers helping render html elements

### Forms

#### Form : `TwbBundle\Form\View\Helper\TwbBundleForm`

Form helper can be called in a view with the view helper service `form(\Zend\Form\FormInterface $oForm, $sFormLayout = self::LAYOUT_HORIZONTAL)` :

```php
$this->form(new \Zend\Form\Form());
```
This helper accepts a form element as first param, and an optionnal Bootstrap defined layout style definition as second param.
Here are the available layouts :

* `TwbBundle\Form\View\Helper::LAYOUT_HORIZONTAL` : horizontal (default)
* `TwbBundle\Form\View\Helper::LAYOUT_INLINE` : inline

The helper auto add form specific class and `form` role attributes.

#### Button : `TwbBundle\Form\View\Helper\TwbBundleFormButton`

Button helper can be called in a view with the view helper service `formButton(\Zend\Form\ElementInterface $oElement, $sButtonContent = null)` :

```php
$this->formButton(new \Zend\Form\Element());
```
This helper accepts a button element as first param, and an optionnal button content as second param.
It auto add button specific class (`btn` & `btn-default` is no button class is defined) attribute.

The option `glyphicon` (string or array) can be defined to render a glyphicon into the element :
The glyphicon is rendered by the [glyphicon renderer](#glyphicon--twbbundleviewhelpertwbbundleglyphicon).

If the option is a string, it should be the name of the icon (e.g. "star", "search" ...), the glyphicon will prepend the label if exists. If the option is an array, it accept the folling options :
* string `icon` : the name of the icon (e.g. "star", "search" ...).
* string `position` : (optionnal) the position of the glyphicon to prepend or append the button content, `\TwbBundle\Form\View\Helper\TwbBundleFormButton::GLYPHICON_PREPEND` (the default) and `\TwbBundle\Form\View\Helper\TwbBundleFormButton::GLYPHICON_APPEND`.
* array `attributes` : (optionnal) the additional attributes to the glyphicon element

Button can be set as dropdown button by defined the option `dropdown` (array) to the element :
The dropdown is rendered by the [dropdown renderer](#dropdown--twbbundleviewhelpertwbbundledropdown), it accept the folling additionnal options :
* boolean `split` : the button element and the carret are splitted.
* boolean `dropup` : render a dropup element instead of a dropdown.

The option `disable-twb` can be passed to the element to disable rendering it in a `div` container.

#### Checkbox : `TwbBundle\Form\View\Helper\TwbBundleFormCheckbox`

Checkbox helper can be called in a view with the view helper service `formCheckbox(\Zend\Form\Checkbox $oElement)` :

```php
$this->formCheckbox(new \Zend\Form\Element\Checkbox('checkbox-input'));
```
This helper accepts a checkbox element as first param. As the input is rendered into a label element, the label position (append by default) can passed as an option
The option `disable-twb` (boolean) can be passed to the element to disable rendering it in a `label`.

```php
$this->formCheckbox(new \Zend\Form\Element\Checkbox('checkbox-input',array(
    'label' => 'Prepend label',
    'label_options' => array('position' => \Zend\Form\View\Helper\FormRow::LABEL_PREPEND)
)));
```

#### Form collection : `TwbBundle\Form\View\Helper\TwbBundleFormCollection`

Form collection helper can be called in a view with the view helper service `formCollection(\Zend\Form\ElementInterface $oElement)` :

```php
$this->formCollection(new \Zend\Form\Element());
```
This helper accepts a form collection (fieldset) element as first param.

#### Form element : `TwbBundle\Form\View\Helper\TwbBundleFormElement`

Form element helper can be called in a view with the view helper service `formElement(\Zend\Form\ElementInterface $oElement)` :

```php
$this->formElement(new \Zend\Form\Element());
```
This helper accepts a form element as first param. This helper can render prepend and/or append add-on by setting the appropriate option `add-on-prepend` and/or `add-on-append` to the element.
These options accept the following values :
* `Zend\Form\ElementInterface` : the element will be rendered in the add-on
* `scalar` : the value will be translated and rendered in the add-on
* `array` :
    The array accept once of the following keys :
    * `text` (scalar) : the value will be translated and rendered in the add-on
    * `element` (array) : An element will be created by \Zend\Form\Factory and the given array, then rendered in the add-on
    * `element` (Zend\Form\ElementInterface) : the element will be rendered in the add-on

#### Form element errors : `TwbBundle\Form\View\Helper\TwbBundleFormElementErrors`

Form element errors helper can be called in a view with the view helper service `formElementErrors(\Zend\Form\ElementInterface $oElement)` :

```php
$this->formElementErrors(new \Zend\Form\Element());
```
This helper accepts a form element as first param. This helper render element's errors.

#### Multi-Checkbox : `TwbBundle\Form\View\Helper\TwbBundleFormMultiCheckbox`

Multi-Checkbox helper can be called in a view with the view helper service `formMultiCheckbox(\Zend\Form\ElementInterface $oElement)` :

```php
$this->formMultiCheckbox(new \Zend\Form\Element\ElementInterface());
```
This helper accepts an element as first param.
The option `inline` (boolean) can be passed to the element to display checkoxes inlined (default) or not.

#### Radio : `TwbBundle\Form\View\Helper\TwbBundleFormRadio`

Radio helper can be called in a view with the view helper service `formCheckbox(\Zend\Form\ElementInterface $oElement)` :

```php
$this->formRadio(new \Zend\Form\Element\ElementInterface());
```
This helper accepts an element as first param.
The option `disable-twb` (boolean) can be passed to the element to disable rendering it in a `div` container.

#### Form row : `TwbBundle\Form\View\Helper\TwbBundleFormRow`

Form element helper can be called in a view with the view helper service `formRow(\Zend\Form\ElementInterface $oElement)` :

```php
$this->formRow(new \Zend\Form\Element());
```
This helper accepts a form element as first param.
The option `twb-layout` (string) can be passed to the element to render the row with a defined layout style as [form helper](#form--twbbundleformviewhelpertwbbundleform).
The option `validation-state` (string) can be passed to the element to render the row with a defined validation state class attribute(`has-...`). If the element has messages, `has-error` class attribute is auto added.
The option `column-size` (int) can be passed to the element to render the row with a defined column size class attribute(`col-lg-...`).
The option `help-block` (string) can be passed to the element to render an help block translated appending the element.

You can allow the label html rendering after toggling off escape :

```php
$this->formRow(new \Zend\Form\Element('my-element',  array(
    'label' => 'My <i>Label</i> :', 
    'label_options' => array('disable_html_escape' => true)
)));
```

#### Static : `TwbBundle\Form\View\Helper\TwbBundleFormStatic`

Static helper can be called in a view with the view helper service `formStatic(\Zend\Form\ElementInterface $oElement)` :

```php
$this->formStatic(new \Zend\Form\Element\ElementInterface());
```
This helper accepts an element as first param.

### Mixed

#### Alert : `TwbBundle\View\Helper\TwbBundleAlert`

Alert helper can be called in a view with the view helper service `alert($sAlertMessage = null, $aAlertAttributes = null, $bDismissable = false)` :

```php
$this->alert('alert message',array('class' => 'alert-success'));
```
This helper accepts a message as first param, attributes for alert container as second param (optionnal) and boolean as third param to display or not a close action to the alert message (optionnal).
The class attribute "alert" is auto added to the alert container.

#### Badge : `TwbBundle\View\Helper\TwbBundleBadge`

Badge helper can be called in a view with the view helper service `badge($sBadgeMessage = null, array $aBadgeAttributes = null)` :

```php
$this->badge('badge message',array('class' => 'pull-right'));
```
This helper accepts a message as first param, and attributes for badge container as second param (optionnal).
The class attribute "badge" is auto added to the badge container.

#### Button group : `TwbBundle\View\Helper\TwbBundleButtonGroup`

Button group helper can be called in a view with the view helper service `buttonGroup(array $aButtons = null, array $aButtonGroupOptions = null)` :

```php
$this->buttonGroup(array(new \Zend\Form\Element\Button('left', array('label' => 'Left'))),array('class' => 'pull-right'));
```
This helper accepts an array of buttons as first param, and attributes for button group container as second param (optionnal).
The buttons can be instance of `\Zend\Form\Element\Button` or array containing data to build an element with the `\Zend\Form\Factory`

#### Glyphicon : `TwbBundle\View\Helper\TwbBundleGlyphicon`

Glyphicon helper can be called in a view with the view helper service `glyphicon($sGlyphicon = null, array $aGlyphiconAttributes = null)` :

```php
$this->glyphicon('star',array('class' => 'pull-right'));
```
This helper accepts an icon name as first param (e.g. "star", "search" ...), and attributes for glyphicon element as second param (optionnal).
The class attribute "glyphicon" is auto added to the glyphicon container.

#### FontAwesome : `TwbBundle\View\Helper\TwbBundleFontAwesome`

FontAwesome helper can be called in a view with the view helper service `fontAwesome($sFontAwesome = null, array $aFontAwesomeAttributes = null)` :

```php
$this->fontAwesome('star',array('class' => 'pull-right'));
```
This helper accepts an icon name as first param (e.g. "star", "search" ...), and attributes for fontAwesome element as second param (optionnal).
The class attribute "fa" is auto added to the fontAwesome container.

#### Dropdown : `TwbBundle\View\Helper\TwbBundleDropDown`

Dropdown helper can be called in a view with the view helper service `dropdown(array $aDropdownOptions = null)` :

```php
$this->dropdown(array('Item #1',\TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_DIVIDER,'Item #2'));
```
This helper accepts dropdown configuration as first param :
 * `attributes` (array) : attributes for the dropdown container (optionnal)
 * `label` (scalar) : Label content (will be translated), may be empty (optionnal)
 * `toggle_attributes` (array) : attributes for the dropdown toggle container (optionnal)
 * `items` (array) : list of items, should contains :
  * `scalar` :
   * `\TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_DIVIDER` : display a divider
   * `text` : display a text (translated) into a link (anchor attribute is the same as content)
  * `array` : the item options :
   * `type` (string) : the type of item
    * `\TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_HEADER` : render an item as header
    It needs the following option :
     * `label` (scalar) content text  (translated) of the item
      * `\TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_DIVIDER` : render a divider
      * `\TwbBundle\View\Helper\TwbBundleDropDown::TYPE_ITEM_LINK` : render an item as a link `<a ...>`
    It needs the following option :
     * `label` (scalar) content text  (translated) of the item
     * `item_attributes` (array) : attributes for the item container (optionnal)
   * `attributes` (array) : the attributes of the item container
 * `list_attributes` (array) : attributes for the dropdown list container (optionnal)

#### Label : `TwbBundle\View\Helper\TwbBundleLabel`

Label helper can be called in a view with the view helper service `label($sLabelMessage  = null, array $aLabelAttributes  = 'label-default')` :

```php
$this->label('label message',array('class' => 'label-primary'));
```
This helper accepts a message as first param, and attributes for label container as second param (optionnal).
The class attribute "label" is auto added to the label container and "label-default" is no attributes is given. Default label container is a span, but it can be changed by passing the tag name in the attributes array :

```php
$this->label('label message',array('class' => 'label-primary','tagName' => 'a'));
```
## Elements

__TwbBundle__ provides new elements to supports Twitter Bootstrap potential.

#### StaticElement : `TwbBundle\Form\Element\StaticElement`

Static element is a form element witch provides `Static control` and should be rendered by [static form helper](#static--twbbundleformviewhelpertwbbundleformstatic)

```php
$this->formStatic(new \TwbBundle\Form\Element\StaticElement());
```
