# Getting Started With SgDatatablesBundle

This Bundle integrates the jQuery DataTables 1.10.x plugin into your Symfony2 application.

## Installation

### Prerequisites

This bundle requires the following additional packages:

* Symfony 2.3.x
* jQuery 1.11.x
* DataTables 1.10.x
* Moment.js 2.10.x
* FOSJsRoutingBundle @stable ***Please follow all steps described [here](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle/blob/master/Resources/doc/index.md).***

The `require` part of your composer.json might look like this:

```js
    "require": {
        "symfony/symfony": "2.6.*",
        "components/jquery": "1.11.3",
        "datatables/datatables": "1.10.7",
        "moment/moment": "2.10.2",
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

Include the jQuery, DataTables, Moment and FOSJsRoutingBundle javascript/css files in your layout.

## Full examples

- [Example](./example.md)

## List of available column types

- [Columns](./columns.md)

## List of available features and options

- [Features and Options](./options.md)

## To use a line formatter

- [Line formatter](./lineFormatter.md)

## Reference configuration

- [Reference configuration](./configuration.md)
