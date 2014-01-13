# Getting Started With SgDatatablesBundle

This Bundle integrates the jQuery DataTables plugin into your Symfony2 application.
Compatible with Doctrine2 entities.

## Installation

### Prerequisites

This bundle requires the following additional packages:

* Symfony 2.3.x
* jQuery 1.10.x
* DataTables 1.10
* Moment.js 2.5.0
* FOSJsRoutingBundle 1.5.0. ***Please follow all steps described [here](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle/blob/master/Resources/doc/index.md).***

This bundle suggest the installation of the following packages for a bootstrap layout:

* Bootstrap 3.0.x
* MopaBootstrapBundle 3.0 ***Please follow all steps described [here](https://github.com/phiamo/MopaBootstrapBundle).***

For Bootstrap 3.0.x the `require` part of your composer.json might look like this:

```js
    "require": {
        "symfony/framework-bundle": "~2.3",
        "components/jquery": "1.10.2",
        "datatables/datatables": "dev-master",
        "mopa/bootstrap-bundle": "v3.0.0-beta3",
        "twbs/bootstrap": "v3.0.0",
        "moment/moment": "2.5.0",
        "friendsofsymfony/jsrouting-bundle": "@stable"
    },
```

Or use the jQuery-ui themes:

```js
    "require": {
        "symfony/framework-bundle": "~2.3",
        "components/jquery": "1.10.2",
        "datatables/datatables": "dev-master",
        "components/jqueryui": "1.10.3",
        "moment/moment": "2.5.0",
        "friendsofsymfony/jsrouting-bundle": "@stable"
    },
```

Or the base layout:

```js
    "require": {
        "symfony/framework-bundle": "~2.3",
        "components/jquery": "1.10.2",
        "datatables/datatables": "dev-master",
        "moment/moment": "2.5.0",
        "friendsofsymfony/jsrouting-bundle": "@stable"
    },
```

### Translations

``` yaml
# app/config/config.yml

framework:
    translator: { fallback: %locale% }

    # ...

    default_locale:  "%locale%"
```

### Step 1: Download SgDatatablesBundle using composer

If not already done: add SgDatatablesBundle in your composer.json:

```js
{
    "require": {
        "sg/datatablesbundle": "dev-master"
    }
}
```

Now tell composer to download the bundle by running the command:

``` bash
$ php composer.phar update sg/datatablesbundle
```

Or get the latest versions of all bundles:

``` bash
$ php composer.phar update
```

### Step 2: Enable the bundle

Enable the bundle in the kernel:

``` php
<?php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = array(
        // ...
        new Sg\DatatablesBundle\SgDatatablesBundle(),
    );
}
```

### Step 3: Assetic Configuration

A config example:

``` yaml
# app/config/config.yml

assetic:
    debug:          "%kernel.debug%"
    use_controller: false
    java: /usr/bin/java
    filters:
        less:
            node: /usr/bin/node
            node_paths: [/usr/lib/nodejs, /usr/local/lib/node_modules]
            apply_to: "\.less$"
        cssrewrite: ~
        cssembed:
            jar: %kernel.root_dir%/Resources/java/cssembed-0.4.5.jar
        yui_css:
            jar: %kernel.root_dir%/Resources/java/yuicompressor-2.4.8.jar
        yui_js:
            jar: %kernel.root_dir%/Resources/java/yuicompressor-2.4.8.jar
```

#### Bootstrap 3.0.x

***For Bootstrap 3.0.x it is recommended to install the [MopaBootstrapBundle](https://github.com/phiamo/MopaBootstrapBundle).***

A layout.html.twig example:

``` html
{% extends 'MopaBootstrapBundle::base.html.twig' %}

{% block title %}ExampleBundle{% endblock %}

{% block head_style %}

    {% stylesheets
        '@MopaBootstrapBundle/Resources/public/less/mopabootstrapbundle.less'
        '%kernel.root_dir%/../vendor/datatables/datatables/examples/resources/bootstrap/3/dataTables.bootstrap.css'
        output = 'css/styles.css'
        filter = 'cssembed, ?yui_css'
    %}
        <link href="{{ asset_url }}" type="text/css" rel="stylesheet" media="screen" />
    {% endstylesheets %}

{% endblock head_style %}

{% block head_script %}

    {% javascripts
        '%kernel.root_dir%/../vendor/components/jquery/jquery.js'
        output = 'js/jquery.js'
        filter = '?yui_js'
    %}
        <script type="text/javascript" src="{{ asset_url }}"></script>
    {% endjavascripts %}

{% endblock head_script %}

{% block foot_script_assetic %}

    {% javascripts
        '@MopaBootstrapBundle/Resources/public/bootstrap/js/tooltip.js'
        '@MopaBootstrapBundle/Resources/public/bootstrap/js/affix.js'
        '@MopaBootstrapBundle/Resources/public/bootstrap/js/alert.js'
        '@MopaBootstrapBundle/Resources/public/bootstrap/js/button.js'
        '@MopaBootstrapBundle/Resources/public/bootstrap/js/carousel.js'
        '@MopaBootstrapBundle/Resources/public/bootstrap/js/collapse.js'
        '@MopaBootstrapBundle/Resources/public/bootstrap/js/dropdown.js'
        '@MopaBootstrapBundle/Resources/public/bootstrap/js/modal.js'
        '@MopaBootstrapBundle/Resources/public/bootstrap/js/popover.js'
        '@MopaBootstrapBundle/Resources/public/bootstrap/js/scrollspy.js'
        '@MopaBootstrapBundle/Resources/public/bootstrap/js/tab.js'
        '@MopaBootstrapBundle/Resources/public/bootstrap/js/transition.js'
        '@MopaBootstrapBundle/Resources/public/js/mopabootstrap-collection.js'
        '@MopaBootstrapBundle/Resources/public/js/mopabootstrap-subnav.js'
        '@FOSJsRoutingBundle/Resources/public/js/router.js'
        '%kernel.root_dir%/../vendor/datatables/datatables/media/js/jquery.dataTables.js'
        '%kernel.root_dir%/../vendor/datatables/datatables/examples/resources/bootstrap/3/dataTables.bootstrap.js'
        '%kernel.root_dir%/../vendor/moment/moment/moment.js'
        '%kernel.root_dir%/../vendor/moment/moment/lang/de.js'
        output = 'js/scripts.js'
        filter = '?yui_js'
    %}
        <script type="text/javascript" src="{{ asset_url }}"></script>
    {% endjavascripts %}

    <script src="{{ path('fos_js_routing_js', {"callback": "fos.Router.setData"}) }}"></script>

{% endblock foot_script_assetic %}
```

#### jQuery-ui themes

A layout.html.twig example:

``` html
```

#### DataTables base layout

A layout.html.twig example:

``` html
```

## Examples

- [Examples](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/example.md)

## List of available column types

- [columns](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/columns.md)