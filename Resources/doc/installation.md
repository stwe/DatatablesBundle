# Getting Started With SgDatatablesBundle

This Bundle integrates the jQuery DataTables 1.10.x plugin into your Symfony application.

## Installation

### Prerequisites

This bundle requires the following additional packages:

* Symfony 2.6.x or 3.0
* jQuery 1.11.x
* DataTables 1.10.x
* Moment.js 2.10.x
* FOSJsRoutingBundle @stable ***Please follow all steps described [here](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle/blob/master/Resources/doc/index.md).***

The `require` part of your composer.json might look like this:

```js
    "require": {
        "symfony/symfony": "2.6.*",
        "components/jquery": "1.11.3",
        "datatables/datatables": "1.10.10",
        "moment/moment": "2.10.6",
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
        "sg/datatablesbundle": "v0.8"
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

Include the jQuery, DataTables, Moment and FOSJsRoutingBundle javascript/css files in your base layout.

CDN example with Bootstrap3:

```html
<head>
    <meta charset="UTF-8" />
    <title>{% block title %}Welcome!{% endblock %}</title>
    {% block stylesheets %}
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" >
        <link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker-bs3.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/s/bs-3.3.5/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.10,b-1.1.0,b-colvis-1.1.0,b-flash-1.1.0,b-html5-1.1.0,b-print-1.1.0,r-2.0.0/datatables.min.css"/>
    {% endblock %}
    {% block head_javascripts %}
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment-with-locales.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker.js"></script>
        <script src="https://cdn.datatables.net/s/bs-3.3.5/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.10,b-1.1.0,b-colvis-1.1.0,b-flash-1.1.0,b-html5-1.1.0,b-print-1.1.0,r-2.0.0/datatables.min.js"></script>
        <script src="{{ asset('bundles/fosjsrouting/js/router.js') }}"></script>
        <script src="{{ path('fos_js_routing_js', {'callback': 'fos.Router.setData'}) }}"></script>
    {% endblock %}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>
```

### Step 4: Create your Datatable class

``` bash
$ php app/console sg:datatable:generate AppBundle:Post
```

### Step 5: Registering your Datatable class as a Service

```yaml
app.datatable.post:
    class: AppBundle\Datatables\PostDatatable
    tags:
        - { name: sg.datatable.view }
```

### Step 6: Create your index.html.twig

```html
{% extends '::base.html.twig' %}

{% block body %}
    {{ datatable_render(datatable) }}
{% endblock %}
```

### Step 7: Add controller actions

```php
/**
 * @Route("/", name="post")
 * @Method("GET")
 * @Template(":post:index.html.twig")
 *
 * @return array
 */
public function indexAction()
{
    $datatable = $this->get('app.datatable.post');
    $datatable->buildDatatable();

    return array(
        'datatable' => $datatable,
    );
}

/**
 * @Route("/results", name="post_results")
 *
 * @return \Symfony\Component\HttpFoundation\Response
 */
public function indexResultsAction()
{
    $datatable = $this->get('app.datatable.post');
    $datatable->buildDatatable();

    $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

    return $query->getResponse();
}
```

### Step 8: Update route annotations for FOSJsRoutingBundle 

Actions like `new`, `show` or `edit` should be updated. 

Add `options`: 

```php
options={"expose"=true}
```

Example: 

```php
    /**
     * Displays a form to create a new Post entity.
     *
     * @Route("/new", name="post_new", options={"expose"=true})
     * @Method("GET")
     * @Template(":post:new.html.twig")
     */
    public function newAction()
```
