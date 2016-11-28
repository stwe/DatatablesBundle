# Getting Started With SgDatatablesBundle 1.0

This Bundle integrates the jQuery DataTables 1.10.12 (or higher) plugin into your Symfony3 application.

1. [Installation](#installation)
2. [Your First Datatable](#your-first-datatable)

## Installation

### Prerequisites

This bundle requires the following additional packages:

* PHP 5.5.9
* Doctrine 2.5
* Symfony 3.x.x
* jQuery (choose a Version, I use 3.1.x)
* DataTables 1.10.12 or higher
* Moment.js 2.x.x (choose a Version, I use 2.15.x)
* FOSJsRoutingBundle 1.6 ***Please follow all steps described [here](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle/blob/master/Resources/doc/installation.rst).***

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


### Step 4: Assetic Configuration

#### Install the web assets

``` bash
# if possible, make absolute symlinks (best practice) in web/ if not, make a hard copy

$ php bin/console assets:install --symlink
```

``` bash
# make a hard copy of assets in web/

$ php bin/console assets:install
```

```html
<script src="{{ asset('bundles/sgdatatables/js/pipeline.js') }}"></script>
```

#### 3rd Party css/javascript dependencies

This Bundle has some 3rd Party css/javascript dependencies.

The easiest way is to load all files with your base layout with CDN.

**Example:**

```html
{% block stylesheets %}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/b-1.2.2/b-colvis-1.2.2/b-flash-1.2.2/b-html5-1.2.2/b-print-1.2.2/fc-3.2.2/fh-3.1.2/r-2.1.0/datatables.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/daterangepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css"/>
    <link rel="stylesheet" href="//cdn.rawgit.com/noelboss/featherlight/1.5.1/release/featherlight.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.2.0/css/bootstrap-slider.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css">
{% endblock %}
{% block head_javascripts %}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment-with-locales.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdn.datatables.net/v/bs/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/b-1.2.2/b-colvis-1.2.2/b-flash-1.2.2/b-html5-1.2.2/b-print-1.2.2/fc-3.2.2/fh-3.1.2/r-2.1.0/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.24/daterangepicker.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
    <script src="//cdn.rawgit.com/noelboss/featherlight/1.5.1/release/featherlight.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/featherlight/1.5.0/featherlight.gallery.min.js"></script>
    <script src="https://raw.githubusercontent.com/bartaz/sandbox.js/master/jquery.highlight.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/9.2.0/bootstrap-slider.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js"></script>

    <script src="{{ asset('bundles/sgdatatables/js/pipeline.js') }}"></script>

    <script src="{{ asset('bundles/fosjsrouting/js/router.js') }}"></script>
    <script src="{{ path('fos_js_routing_js', {'callback': 'fos.Router.setData'}) }}"></script>
{% endblock %}
```

## Your First Datatable

### Step 1: Create a Datatable class

```php
<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

/**
 * Class PostDatatable
 *
 * @package AppBundle\Datatables
*/
class PostDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $formatter = function($row) {
            $row['test'] = 'custom content';

            return $row;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->ajax->set(array(
            'data' => array('data1' => 1, 'data2' => 2),
            'pipeline' => 10
        ));

        $this->options->set(array(
            'stripe_classes' => [ 'strip1', 'strip2', 'strip3' ],
            'individual_filtering' => true,
            'individual_filtering_position' => 'both',
            'order_cells_top' => true
        ));

        $this->columnBuilder
            ->add('id', Column::class, array(
                'title' => 'Id',
                'searchable' => false,
                'orderable' => true,
            ))
            ->add('test', VirtualColumn::class, array(
                'title' => 'Test virtual',
                'searchable' => false,
                'orderable' => false,
                'order_column' => 'title',
                'search_column' => 'createdBy.username',
            ))
            ->add('title', Column::class, array(
                'title' => 'Title',
                'searchable' => true,
                'orderable' => true,
                //'type_of_field' => 'integer'
            ))
            ->add('createdBy.username', Column::class, array(
                'title' => 'Username',
                'searchable' => true,
                'orderable' => true,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\Post';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'post_datatable';
    }
}
```

### Step 2: Registering your Datatable as a Service

```yaml
# app/config/services.yml

services:
    app.datatable.post:
        class: AppBundle\Datatables\PostDatatable
        parent: sg_datatables.datatable.abstract
```

### Step 3: Add controller action

```php
/**
 * Lists all Post entities.
 *
 * @param Request $request
 *
 * @Route("/", name="post_index")
 * @Method("GET")
 *
 * @return Response
 */
public function indexAction(Request $request)
{
    $isAjax = $request->isXmlHttpRequest();

    $datatable = $this->get('app.datatable.post');
    $datatable->buildDatatable();

    if ($isAjax) {
        $responseService = $this->get('sg_datatables.response');
        $responseService->setDatatable($datatable);

        $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
        $datatableQueryBuilder->buildQuery();

        return $responseService->getResponse();
    }

    return $this->render('post/index.html.twig', array(
        'datatable' => $datatable,
    ));
}
```

### Step 4: Create your index.html.twig

```html
{% extends '::base.html.twig' %}

{% block body %}
    <h2>Posts</h2>
    {{ sg_datatable_render(datatable) }}
{% endblock %}
```
