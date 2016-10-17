# Getting Started With SgDatatablesBundle

This Bundle integrates the jQuery DataTables 1.10.x plugin into your Symfony application.

1. [Installation](#installation)
2. [Your First Datatable](#your-first-datatable)

## Installation

### Prerequisites

This bundle requires the following additional packages:

* Symfony 2.6.x or 3.0.x
* jQuery 1.12.x
* DataTables 1.10.x
* Moment.js 2.11.x
* FOSJsRoutingBundle 1.6 ***Please follow all steps described [here](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle/blob/master/Resources/doc/installation.rst).***

This bundle provides support for displaying uploaded images. For proper display of images as thumbnails the LiipImagineBundle is required.
Please follow all steps as described [here](http://symfony.com/doc/master/bundles/LiipImagineBundle/installation.html).

To upload images, I recommend the VichUploaderBundle. You can follow all steps as described [here](https://github.com/dustin10/VichUploaderBundle/blob/master/Resources/doc/index.md).

### Translations

``` yaml
# app/config/config.yml

framework:
    translator: { fallbacks: ["%locale%"] }

    # ...

    default_locale: "%locale%"
```

### Step 1: Download the Bundle

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

``` bash
$ composer require sg/datatablesbundle
```

### Step 2: Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles in the `app/AppKernel.php` file of your project:

``` php
<?php
// app/AppKernel.php

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            // ...
            new Sg\DatatablesBundle\SgDatatablesBundle(),
        );
    }
}
```

### Step 3: Load the Routes of the Bundle

Load the routes of the bundle by adding this configuration to the `app/config/routing.yml` file:

```yaml
# app/config/routing.yml
sg_datatables_bundle:
    resource: "@SgDatatablesBundle/Controller/"
    type:     annotation
```

### Step 4: Assetic Configuration

This Bundle has some 3rd Party css/javascript dependencies.

[DataTables](https://datatables.net/) is mandatory. This bundle is optimized for [Bootstrap3](http://getbootstrap.com/).

| DatatablesBundle Feature | Plugin / Github-Link                                                                  | Relies on Bootstrap |
|--------------------------|---------------------------------------------------------------------------------------|---------------------|
| ProgressBar-Column       | [Bootstrap3](http://getbootstrap.com/)                                                | yes                 |
| DateRange-Filter         | [Bootstrap-Daterangepicker](https://github.com/dangrossman/bootstrap-daterangepicker) | yes                 |
| Slider-Filter            | [Bootstrap-Slider](https://github.com/seiyria/bootstrap-slider)                       | yes                 |
| In-place editing         | [X-editable](https://github.com/vitalets/x-editable)                                  | no                  |
| Enlarge thumbnails       | [Featherlight](https://github.com/noelboss/featherlight/)                             | no                  |
| Highlight search results | [jQuery Highlight Plugin](https://github.com/bartaz/sandbox.js)                       | no                  |
| Select2-Filter           | [Select2](https://github.com/select2/select2)                                         | no                  |

Load all files with your base layout.

Full example with CDN:

```html
<head>
    <meta charset="UTF-8" />
    <title>{% block title %}SgDatatablesBundleDemo{% endblock %}</title>
    {% block stylesheets %}
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.20/daterangepicker.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/u/bs/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.12,b-1.2.1,b-colvis-1.2.1,b-flash-1.2.1,b-html5-1.2.1,b-print-1.2.1,r-2.1.0/datatables.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/featherlight/1.4.1/featherlight.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.1.0/css/bootstrap-slider.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" />
    {% endblock %}
    {% block head_javascripts %}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment-with-locales.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.20/daterangepicker.min.js"></script>
        <script src="https://cdn.datatables.net/u/bs/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.12,b-1.2.1,b-colvis-1.2.1,b-flash-1.2.1,b-html5-1.2.1,b-print-1.2.1,r-2.1.0/datatables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/featherlight/1.4.1/featherlight.min.js"></script>
        <script src="https://raw.githubusercontent.com/bartaz/sandbox.js/master/jquery.highlight.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.1.0/bootstrap-slider.min.js"></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js'></script>
        <script src="{{ asset('bundles/fosjsrouting/js/router.js') }}"></script>
        <script src="{{ path('fos_js_routing_js', {'callback': 'fos.Router.setData'}) }}"></script>
    {% endblock %}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>
```

## Your First Datatable

### Step 1: 

The `sg:datatable:generate` command generates a datatable class for a given entity located in a given bundle.

To activate the popular Bootstrap3 layout, use the --bootstrap3 option:

``` bash
$ php app/console sg:datatable:generate AppBundle:Post --bootstrap3
```

If your application is based on Symfony 3, replace `php app/console` by `php bin/console`.

### Step 2: Registering your Datatable as a Service

```yaml
services:
    app.datatable.post:
        class: AppBundle\Datatables\PostDatatable
        parent: sg_datatables.datatable.abstract
```

### Step 3: Add controller actions

```php
/**
 * @Route("/", name="post_index")
 * @Method("GET")
 */
public function indexAction()
{
    $datatable = $this->get('app.datatable.post');
    $datatable->buildDatatable();

    return $this->render('post/index.html.twig', array(
        'datatable' => $datatable,
    ));
}

/**
 * @Route("/results", name="post_results")
 */
public function indexResultsAction()
{
    $datatable = $this->get('app.datatable.post');
    $datatable->buildDatatable();

    $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

    return $query->getResponse();
}
```

### Step 4: Create your index.html.twig

```html
{% extends '::base.html.twig' %}

{% block body %}
    {{ datatable_render(datatable) }}
{% endblock %}
```

### Step 5: Update your route annotations for the FOSJsRoutingBundle 

Actions like `show` or `edit` should be updated. 

Add `options`: 

```php
options={"expose"=true}
```

Example: 

```php
/**
 * Displays a form to edit an existing Post entity.
 *
 * @Route("/{id}/edit", name="post_edit", options={"expose"=true})
 * @Method({"GET", "POST"})
 */
public function editAction(Request $request, Post $post)
{
    // ...
}
```
