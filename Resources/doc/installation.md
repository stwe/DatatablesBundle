# Getting Started With SgDatatablesBundle

This Bundle integrates the jQuery DataTables 1.10.x plugin into your Symfony application.

## Installation

### Prerequisites

This bundle requires the following additional packages:

* Symfony 2.6.x or 3.0.x
* jQuery 1.12.x
* DataTables 1.10.x
* Moment.js 2.11.x
* FOSJsRoutingBundle 1.6 ***Please follow all steps described [here](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle/blob/master/Resources/doc/index.md).***

### Translations

``` yaml
# app/config/config.yml

framework:
    translator: { fallbacks: ["%locale%"] }

    # ...

    default_locale: "%locale%"
```

### Step 1: Download SgDatatablesBundle using composer

Require the bundle with composer:

``` bash
$ composer require sg/datatablesbundle
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

CDN example with Bootstrap3, Daterangepicker and X-Editable:

```html
<head>
    <meta charset="UTF-8" />
    <title>{% block title %}SgDatatablesBundleDemo{% endblock %}</title>
    {% block stylesheets %}
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
        <link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/s/bs-3.3.5/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.10,b-1.1.0,b-colvis-1.1.0,b-flash-1.1.0,b-html5-1.1.0,b-print-1.1.0,r-2.0.0/datatables.min.css">
        <link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet"/>
    {% endblock %}
    {% block head_javascripts %}
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment-with-locales.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
        <script src="https://cdn.datatables.net/s/bs-3.3.5/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.10,b-1.1.0,b-colvis-1.1.0,b-flash-1.1.0,b-html5-1.1.0,b-print-1.1.0,r-2.0.0/datatables.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
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
    parent: sg_datatables.view.abstract
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
 * Lists all Post entities.
 *
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
