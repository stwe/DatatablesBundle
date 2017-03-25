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
* jQuery (choose a Version, I use 3.1.1)
* DataTables 1.10.12 or higher
* Moment.js 2.8.4 or higher (choose a Version, I use 2.15.1)
* FOSJsRoutingBundle 1.6.0 ***Please follow all steps described [here](https://github.com/FriendsOfSymfony/FOSJsRoutingBundle/blob/1.6.0/Resources/doc/index.md).***

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

Load the routes of the bundle by adding this configuration to the app/config/routing.yml file:

``` yml
# app/config/routing.yml
sg_datatables_bundle:
    resource: "@SgDatatablesBundle/Controller/"
    type:     annotation
```

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

``` html
<script src="{{ asset('bundles/sgdatatables/js/pipeline.js') }}"></script>
```

#### 3rd Party css/javascript dependencies

This Bundle has some 3rd Party css/javascript dependencies.

The easiest way is to load all files with your base layout with CDN.

**Example:**

``` html
<head>
    <meta charset="UTF-8" />
    <title>{% block title %}SgDatatablesBundleDev{% endblock %}</title>
    {% block stylesheets %}
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/b-1.2.2/b-colvis-1.2.2/b-flash-1.2.2/b-html5-1.2.2/b-print-1.2.2/fc-3.2.2/fh-3.1.2/r-2.1.0/datatables.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.25/daterangepicker.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css">
        <link rel="stylesheet" href="https://cdn.rawgit.com/noelboss/featherlight/1.7.1/release/featherlight.min.css">
    {% endblock %}
    {% block head_javascripts %}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment-with-locales.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.25/daterangepicker.min.js" charset="UTF-8"></script>
        <script src="https://cdn.datatables.net/v/bs/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/b-1.2.2/b-colvis-1.2.2/b-flash-1.2.2/b-html5-1.2.2/b-print-1.2.2/fc-3.2.2/fh-3.1.2/r-2.1.0/datatables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
        <script src="https://cdn.rawgit.com/noelboss/featherlight/1.7.1/release/featherlight.min.js"></script>

        <script src="{{ asset('bundles/sgdatatables/js/pipeline.js') }}"></script>

        <script src="{{ asset('bundles/fosjsrouting/js/router.js') }}"></script>
        <script src="{{ path('fos_js_routing_js', {'callback': 'fos.Router.setData'}) }}"></script>
    {% endblock %}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>
```

## Your First Datatable

### Step 1: Create a Datatable class

The `sg:datatable:generate` [Console Command](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/command.md) generates a Datatable for a given entity located in a given bundle.

**Example:**

``` bash
$ php bin/console sg:datatable:generate AppBundle:Post
```

**You can also write the class yourself.**

**Here's an example:**

``` php
<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\MultiselectColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Editable\SelectEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextEditable;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;
use Sg\DatatablesBundle\Datatable\Filter\NumberFilter;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Style;

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
            $row['test'] = 'Post from ' . $row['createdBy']['username'];

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
            // send some extra example data
            'data' => array('data1' => 1, 'data2' => 2),
            // cache for 10 pages
            'pipeline' => 10
        ));

        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_3_STYLE,
            'stripe_classes' => [ 'strip1', 'strip2', 'strip3' ],
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order' => array(array(0, 'asc')),
            'order_cells_top' => true,
            //'global_search_type' => 'gt',
            'search_in_non_visible_columns' => true,
        ));

        $users = $this->em->getRepository('AppBundle:User')->findAll();

        $this->columnBuilder
            ->add('id', Column::class, array(
                'title' => 'Id',
                'searchable' => true,
                'orderable' => true,
                'filter' => array(NumberFilter::class, array(
                    'classes' => 'test1 test2',
                    'search_type' => 'eq',
                    'cancel_button' => true,
                    'type' => 'number',
                    'show_label' => true,
                    'datalist' => array('3', '50', '75')
                )),
            ))
            ->add('visible', BooleanColumn::class, array(
                'title' => 'Visible',
                'searchable' => true,
                'orderable' => true,
                'true_label' => 'Yes',
                'false_label' => 'No',
                'default_content' => 'Default Value',
                'true_icon' => 'glyphicon glyphicon-ok',
                'false_icon' => 'glyphicon glyphicon-remove',
                'filter' => array(SelectFilter::class, array(
                    'classes' => 'test1 test2',
                    'search_type' => 'eq',
                    'multiple' => true,
                    'select_options' => array(
                        '' => 'Any',
                        '1' => 'Yes',
                        '0' => 'No'
                    ),
                    'cancel_button' => true,
                )),
                'editable' => array(SelectEditable::class, array(
                    'source' => array(
                        array('value' => 1, 'text' => 'Yes'),
                        array('value' => 0, 'text' => 'No'),
                        array('value' => null, 'text' => 'Null')
                    ),
                    'mode' => 'inline',
                    //'pk' => 'cid',
                    'empty_text' => '',
                )),
            ))
            ->add('title', Column::class, array(
                'title' => 'Title',
                'searchable' => true,
                'orderable' => true,
                'filter' => array(SelectFilter::class, array(
                    'multiple' => true,
                    'cancel_button' => true,
                    'select_search_types' => array(
                        '' => null,
                        '2' => 'like',
                        '1' => 'eq',
                        'send_isNull' => 'isNull',
                        'send_isNotNull' => 'isNotNull'
                    ),
                    'select_options' => array(
                        '' => 'Any',
                        '2' => 'Title with the digit 2',
                        '1' => 'Title with the digit 1',
                        'send_isNull' => 'is Null',
                        'send_isNotNull' => 'is not Null'
                    ),
                )),
                'editable' => array(TextEditable::class, array(
                    //'pk' => 'cid',
                )),
                'type_of_field' => 'integer', // If the title consists only of digits.
                /*
                'add_if' => function() {
                    return $this->authorizationChecker->isGranted('ROLE_USER');
                },
                */
            ))
            ->add('test', VirtualColumn::class, array(
                'title' => 'Test virtual',
                'searchable' => true,
                'orderable' => true,
                'order_column' => 'createdBy.username', // use the 'createdBy.username' column for ordering
                'search_column' => 'createdBy.username', // use the 'createdBy.username' column for searching
            ))
            ->add('createdBy.username', Column::class, array(
                'title' => 'Created by',
                'searchable' => true,
                'orderable' => true,
                'filter' => array(SelectFilter::class, array(
                    'select_options' => array('' => 'All') + $this->getOptionsArrayFromEntities($users, 'username', 'username'),
                    'search_type' => 'eq'
                ))
            ))
            ->add('comments.title', Column::class, array(
                'title' => 'Comments',
                'data' => 'comments[,].title',
                'searchable' => true,
                'orderable' => true,
            ))
            ->add(null, ActionColumn::class, array(
                'title' => 'Actions',
                'start_html' => '<div class="start_actions">',
                'end_html' => '</div>',
                'actions' => array(
                    array(
                        'route' => 'post_show',
                        'label' => 'Show Posting',
                        'route_parameters' => array(
                            'id' => 'id',
                            '_format' => 'html',
                            '_locale' => 'en'
                        ),
                        'render_if' => function($row) {
                            return $row['createdBy']['username'] === 'user' && $this->authorizationChecker->isGranted('ROLE_USER');
                        },
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => 'Show',
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                        'start_html' => '<div class="start_show_action">',
                        'end_html' => '</div>',
                    )
                )
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

### Step 2: (Optional) Registering your Datatable as a Service

``` yaml
# app/config/services.yml

services:
    app.datatable.post:
        class: AppBundle\Datatables\PostDatatable
        parent: sg_datatables.datatable.abstract
```

### Step 3: The Controller actions

``` php
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

    // Get your Datatable ...
    //$datatable = $this->get('app.datatable.post');
    //$datatable->buildDatatable();

    // or use the DatatableFactory
    /** @var DatatableInterface $datatable */
    $datatable = $this->get('sg_datatables.factory')->create(PostDatatable::class);
    $datatable->buildDatatable();

    if ($isAjax) {
        $responseService = $this->get('sg_datatables.response');
        $responseService->setDatatable($datatable);

        $datatableQueryBuilder = $responseService->getDatatableQueryBuilder();
        $datatableQueryBuilder->buildQuery();

        //dump($datatableQueryBuilder->getQb()->getDQL()); die();

        return $responseService->getResponse();
    }

    return $this->render('post/index.html.twig', array(
        'datatable' => $datatable,
    ));
}

/**
 * Finds and displays a Post entity.
 *
 * @param Post $post
 *
 * @Route("/{_locale}/{id}.{_format}", name = "post_show", options = {"expose" = true})
 * @Method("GET")
 * @Security("has_role('ROLE_USER')")
 *
 * @return Response
 */
public function showAction(Post $post)
{
    return $this->render('post/show.html.twig', array(
        'post' => $post
    ));
}
```

### Step 4: Create your index.html.twig

``` html
{% extends '::base.html.twig' %}

{% block main %}

    <h2>Posts</h2>
    {{ sg_datatables_render(datatable) }}

{% endblock %}
```
