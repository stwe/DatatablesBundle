# Examples

## Server side example

### Step 1: Create your Datatables class

There are two options: You write the class by hand (recommended) or use the command line (unstable).

The `datatable:generate:class` command generates a new datatable class.

The command is run in a non interactive mode.
``` bash
$ php app/console datatable:generate:class MyTestBundle:Entity
```

A description of all available options of the generator is located [here](./generator.md).

The generator is currently in an early development stage. Better you write the class by hand. Then it should look something like this:

```php
<?php

namespace AppBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class PostDatatable
 *
 * @package AppBundle\Datatables
 */
class PostDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $formatter = function($line){
            $line["custom"] = $line["title"] . " published at " . $line["publishedAt"]->format("Y-m-d H:i:s");

            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        // the default settings
        $this->features->setFeatures(array(
            "auto_width" => true,
            "defer_render" => false,
            "info" => true,
            "jquery_ui" => false,
            "length_change" => true,
            "ordering" => true,
            "paging" => true,
            "processing" => true,
            "scroll_x" => false,
            "scroll_y" => "",
            "searching" => true,
            "server_side" => true,
            "state_save" => false,
            "delay" => 0
        ));

        // the default settings, except "url"
        $this->ajax->setOptions(array(
            "url" => $this->getRouter()->generate('post_results'),
            "type" => "GET"
        ));

        // the default settings, except "class" and "use_integration_options"
        $this->options->setOptions(array(
            "display_start" => 0,
            "dom" => "lfrtip", // default, but not used because "use_integration_options" = true
            "length_menu" => array(10, 25, 50, 100),
            "order_classes" => true,
            "order" => array("column" => 0, "direction" => "asc"),
            "order_multi" => true,
            "page_length" => 10,
            "paging_type" => Style::FULL_NUMBERS_PAGINATION,
            "renderer" => "", // default, but not used because "use_integration_options" = true
            "scroll_collapse" => false,
            "search_delay" => 0,
            "state_duration" => 7200,
            "stripe_classes" => array(),
            "responsive" => false,
            "class" => Style::BOOTSTRAP_3_STYLE,
            "individual_filtering" => false,
            "use_integration_options" => true
        ));

        $this->columnBuilder
            ->add("id", "column", array(
                "class" => "",
                "padding" => "",
                "name" => "",
                "orderable" => true,
                "render" => null,
                "searchable" => true,
                "title" => "Id",
                "type" => "",
                "visible" => true,
                "width" => "",
                "default" => ""
            ))
            ->add("visible", "boolean", array(
                "class" => "",
                "padding" => "",
                "name" => "",
                "orderable" => true,
                "render" => "render_boolean",
                "searchable" => true,
                "title" => "Visible",
                "type" => "",
                "visible" => true,
                "width" => "",
                "true_icon" => "glyphicon glyphicon-ok",
                "false_icon" => "",
                "true_label" => "yes",
                "false_label" => "no"
            ))
            ->add("publishedAt", "timeago", array(
                "class" => "",
                "padding" => "",
                "name" => "",
                "orderable" => true,
                "render" => "render_timeago",
                "searchable" => true,
                "title" => "<span class='glyphicon glyphicon-calendar' aria-hidden='true'></span> Published",
                "type" => "",
                "visible" => true,
                "width" => ""
            ))
            ->add("title", "column", array(
                "title" => "<span class='glyphicon glyphicon-book' aria-hidden='true'></span> Title",
            ))
            ->add('custom', 'virtual', array(
                'title' => "Custom Title"
            ))
            ->add("authorEmail", "column", array(
                "class" => "",
                "padding" => "",
                "name" => "",
                "orderable" => true,
                "render" => null,
                "searchable" => true,
                "title" => "<span class='glyphicon glyphicon-user' aria-hidden='true'></span> Author",
                "type" => "",
                "visible" => true,
                "width" => "",
                "default" => ""
            ))
            /*
            ->add('comments.title', 'array', array(
                'title' => "Kommentare",
                //'visible' => true,
                "searchable" => false,
                "orderable" => false,
                "default" => "default value",
                "data" => "comments[, ].title",
                ))
            */
            ->add(null, "action", array(
                "title" => "Actions",
                "start_html" => '<div class="wrapper">',
                "end_html" => '</div>',
                "actions" => array(
                    array(
                        "route" => "post_show",
                        "route_parameters" => array(
                            "id" => "id"
                        ),
                        "label" => "Show",
                        "icon" => "glyphicon glyphicon-eye-open",
                        "attributes" => array(
                            "rel" => "tooltip",
                            "title" => "Show",
                            "class" => "btn btn-default btn-xs",
                            "role" => "button"
                        ),
                        "role" => "ROLE_USER",
                        "render_if" => array("visible")
                    ),
                    array(
                        "route" => "post_edit",
                        "route_parameters" => array(
                            "id" => "id"
                        ),
                        "label" => "Edit",
                        "icon" => "glyphicon glyphicon-edit",
                        "attributes" => array(
                            "rel" => "tooltip",
                            "title" => "Edit",
                            "class" => "btn btn-primary btn-xs",
                            "role" => "button"
                        ),
                        "confirm" => true,
                        "confirm_message" => "Are you sure?",
                        "role" => "ROLE_ADMIN",
                    )
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return "AppBundle\Entity\Post";
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "post_datatable";
    }
}
```

### Step 2: Create your index.html.twig

#### Render entire datatable
```html
{% extends '::base.html.twig' %}

{% block body %}
    {{ datatable_render(datatable) }}
{% endblock %}
```

#### Decouple html and js

```html
{% extends '::base.html.twig' %}

{% block body %}
    {{ datatable_render_html(datatable) }}
{% endblock %}
{% block javascripts %}
    {{ parent() }}
    {{ datatable_render_js(datatable) }}
{% endblock %}
```

### Step 3: Registering your Datatables class as a Service

```yaml
services:
    sg_datatables.post:
        class: AppBundle\Datatables\PostDatatable
        tags:
            - { name: sg.datatable.view }
```

### Step 4: Add controller actions

```php
/**
 * Post datatable.
 *
 * @Route("/", name="post")
 * @Method("GET")
 * @Template(":post:index.html.twig")
 *
 * @return array
 */
public function indexAction()
{
    $postDatatable = $this->get("sg_datatables.post");

    return array(
        "datatable" => $postDatatable,
    );
}

/**
 * Get all Post entities.
 *
 * @Route("/results", name="post_results")
 *
 * @return \Symfony\Component\HttpFoundation\Response
 */
public function indexResultsAction()
{
    /**
     * @var \Sg\DatatablesBundle\Datatable\Data\DatatableData $datatable
     */
    $datatable = $this->get("sg_datatables.datatable")->getDatatable($this->get("sg_datatables.post"));

    return $datatable->getResponse();
}
```

## Non server side example

The differences to the above description:

### Your Datatables class

```php
<?php

namespace Sg\BlogBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Column\ActionColumn;

/**
 * Class PostDatatable
 *
 * @package Sg\BlogBundle\Datatables
 */
class PostDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        $this->features->setFeatures(array(
            "server_side" => false
        ));

        // ...
    }
}
```

### The controller action

```php
/**
 * Post datatable.
 *
 * @Route("/", name="post")
 * @Method("GET")
 * @Template()
 *
 * @return array
 */
public function indexAction()
{
    $repository = $this->getDoctrine()->getRepository('SgBlogBundle:Post');

    $query = $repository->createQueryBuilder('p')
        ->select('p, t, cb, ub')
        ->join('p.tags', 't')
        ->join('p.createdBy', 'cb')
        ->join('p.updatedBy', 'ub')
        ->getQuery();

    $results = $query->getArrayResult();

    $encoders = array(new JsonEncoder());
    $normalizers = array(new GetSetMethodNormalizer());
    $serializer = new Serializer($normalizers, $encoders);

    $postDatatable = $this->get('sg_datatables.post');
    $postDatatable->setData($serializer->serialize($results, 'json'));

    return array(
        'datatable' => $postDatatable,
    );
}
```
