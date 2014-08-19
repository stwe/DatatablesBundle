# Getting Started With SgDatatablesBundle

This Bundle integrates the jQuery DataTables 1.10.x plugin into your Symfony2 application.

## Installation

### Prerequisites

This bundle requires the following additional packages:

* Symfony 2.3.x
* jQuery 1.x
* DataTables 1.10.x
* Moment.js 2.6.x
* FOSJsRoutingBundle 1.5.3. ***Please follow all steps described [here](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle/blob/master/Resources/doc/index.md).***

The `require` part of your composer.json might look like this:

```js
    "require": {
        "symfony/symfony": "2.3.*",
        "components/jquery": "1.11.0",
        "datatables/datatables": "1.10.1",
        "moment/moment": "2.6.0",
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

Or get all bundles:

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

***This is a config example from my Win7 system with Bootstrap3 and MopaBootstrapBundle:***

For Bootstrap 3 it is recommended to:

- install the [MopaBootstrapBundle](https://github.com/phiamo/MopaBootstrapBundle) and
- get the bootstrap3 integrations files from [here] (https://github.com/DataTables/Plugins/tree/master/integration/bootstrap/3)

#### config.yml

``` yaml
# app/config/config.yml

assetic:
    debug:          "%kernel.debug%"
    use_controller: false
    java: C:\Program Files\Java\jre7\bin\java.exe
    filters:
        lessphp:
            file: %kernel.root_dir%/../vendor/leafo/lessphp/lessc.inc.php
            apply_to: "\.less$"
        cssrewrite: ~
        cssembed:
            jar: %kernel.root_dir%/Resources/java/cssembed-0.4.5.jar
        yui_css:
            jar: %kernel.root_dir%/Resources/java/yuicompressor-2.4.8.jar
        yui_js:
            jar: %kernel.root_dir%/Resources/java/yuicompressor-2.4.8.jar
    assets:
        fonts_glyphicons_eot:
            inputs:
               - "%kernel.root_dir%/../vendor/twbs/bootstrap/fonts/glyphicons-halflings-regular.eot"
            output: "fonts/glyphicons-halflings-regular.eot"
        fonts_glyphicons_svg:
            inputs:
                - "%kernel.root_dir%/../vendor/twbs/bootstrap/fonts/glyphicons-halflings-regular.svg"
            output: "fonts/glyphicons-halflings-regular.svg"
        fonts_glyphicons_ttf:
            inputs:
                - "%kernel.root_dir%/../vendor/twbs/bootstrap/fonts/glyphicons-halflings-regular.ttf"
            output: "fonts/glyphicons-halflings-regular.ttf"
        fonts_glyphicons_woff:
            inputs:
                - "%kernel.root_dir%/../vendor/twbs/bootstrap/fonts/glyphicons-halflings-regular.woff"
            output: "fonts/glyphicons-halflings-regular.woff"
```

#### layout.html.twig

``` html
{% extends 'MopaBootstrapBundle::base.html.twig' %}

{% block title %}ExampleBundle{% endblock %}

{% block head_style %}

    {% stylesheets
        '@MopaBootstrapBundle/Resources/public/less/mopabootstrapbundle.less'
        output = 'css/styles.css'
        filter = 'cssembed, ?yui_css'
    %}
        <link href="{{ asset_url }}" type="text/css" rel="stylesheet" media="screen" />
    {% endstylesheets %}

    {# include css file from: https://github.com/DataTables/Plugins/tree/master/integration/bootstrap/3 #}
    <link href="{{ asset('bundles/sgblog/css/dataTables.bootstrap.css') }}" rel="stylesheet" type="text/css" />

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
        '%kernel.root_dir%/../vendor/moment/moment/moment.js'
        '%kernel.root_dir%/../vendor/moment/moment/lang/de.js'
        output = 'js/scripts.js'
        filter = '?yui_js'
    %}
        <script type="text/javascript" src="{{ asset_url }}"></script>
    {% endjavascripts %}

    <script src="{{ path('fos_js_routing_js', {"callback": "fos.Router.setData"}) }}"></script>

    {# include js file from: https://github.com/DataTables/Plugins/tree/master/integration/bootstrap/3 #}
    <script src="{{ asset('bundles/sgblog/js/dataTables.bootstrap.js') }}" type="text/javascript"></script>

{% endblock foot_script_assetic %}
```

## Full examples with the most used functions

- [Example](./example.md)

## List of available column types

- [Columns](./columns.md)

## Used a line formatter

- [Line formatter](./lineFormatter.md)