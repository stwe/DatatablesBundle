# Getting Started With SgDatatablesBundle

This Bundle integrates the jQuery DataTables plugin into your Symfony2 application. Compatible with Doctrine ORM.

*WARNING*: This is not a final/stable bundle.

## Installation

### Prerequisites

* This version of the bundle requires Symfony 2.3.x.
* Bootstrap 2.3.2 and DataTables 1.9 should be installed.
* Finally, FOSJsRoutingBundle needs to be installed and configured beforehand. Please follow all steps described [here](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle/blob/master/Resources/doc/README.markdown).

Your composer.json should look like this:

```js
{
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "datatables/datatables",
                "version": "1.9.4",
                "dist": {
                    "type": "zip",
                    "url": "http://www.datatables.net/releases/DataTables-1.9.4.zip"
                }
            }
        }
    ],
    "require": {
        "datatables/datatables": "1.9.4",
        "twitter/bootstrap": "v2.3.2",
        "friendsofsymfony/jsrouting-bundle": "1.2.0"
}
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
        "sg/datatablesbundle": "v0.2"
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

This bundle provides a layout that uses the Bootstrap2 framework.

``` yaml
# app/config/config.yml

assetic:
    debug:          %kernel.debug%
    use_controller: false
    bundles:        [ XyYourBundle ]
    #java: /usr/bin/java
    filters:
        cssrewrite: ~
    assets:
        jquery_js:
            inputs:
                - %kernel.root_dir%/../vendor/datatables/datatables/media/js/jquery.js
            output: js/jquery.js
        img_bootstrap_glyphicons_black:
            inputs:
                - %kernel.root_dir%/../vendor/twitter/bootstrap/img/glyphicons-halflings.png
            output: img/glyphicons-halflings.png
        img_bootstrap_glyphicons_white:
            inputs:
                - %kernel.root_dir%/../vendor/twitter/bootstrap/img/glyphicons-halflings-white.png
            output: img/glyphicons-halflings-white.png
        bootstrap_css:
            inputs:
                - %kernel.root_dir%/../vendor/twitter/bootstrap/docs/assets/css/bootstrap.css
            output: css/bootstrap.css
        bootstrap_js:
            inputs:
                - %kernel.root_dir%/../vendor/twitter/bootstrap/docs/assets/js/bootstrap.js
            output: js/bootstrap.js
        datatables_js:
            inputs:
                - %kernel.root_dir%/../vendor/datatables/datatables/media/js/jquery.dataTables.min.js
            output: js/datatables.js
        img_sort_both:
            inputs:
                - %kernel.root_dir%/../vendor/datatables/datatables/media/images/sort_both.png
            output: bundles/sgdatatables/images/sort_both.png
        img_sort_asc:
            inputs:
                - %kernel.root_dir%/../vendor/datatables/datatables/media/images/sort_asc.png
            output: bundles/sgdatatables/images/sort_asc.png
        img_sort_desc:
            inputs:
                - %kernel.root_dir%/../vendor/datatables/datatables/media/images/sort_desc.png
            output: bundles/sgdatatables/images/sort_desc.png
        img_sort_asc_dis:
            inputs:
                - %kernel.root_dir%/../vendor/datatables/datatables/media/images/sort_asc_disabled.png
            output: bundles/sgdatatables/images/sort_asc_disabled.png
        img_sort_desc_dis:
            inputs:
                - %kernel.root_dir%/../vendor/datatables/datatables/media/images/sort_desc_disabled.png
            output: bundles/sgdatatables/images/sort_desc_disabled.png
```

## Example

- [An example](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/example.md)

## List of column types

- [column](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/column.md)
- [action](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/action.md)
- [array](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/array.md)