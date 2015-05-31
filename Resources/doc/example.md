# Examples

## Server-Side example

### Step 1: Create your Datatables class

#### Use the command line

The `sg:datatable:generate` command generates a new datatable class.

``` bash
$ php app/console sg:datatable:generate AppBundle:Entity
```

A description of all available options of the generator is located [here](./generator.md).

#### Create the class itself

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
        $formatter = function($line) {
            $repository = $this->container->get("doctrine.orm.entity_manager")->getRepository("SgBlogBundle:Post");
            $entity = $repository->find($line["id"]);

            // see if a User is logged in
            if ($this->container->get("security.authorization_checker")->isGranted("IS_AUTHENTICATED_FULLY")) {
                $user = $this->container->get("security.token_storage")->getToken()->getUser();
                // is the given User the author of this Post?
                $line["owner"] = $entity->isAuthor($user); // render "true" or "false"
            } else {
                // render a twig template with login link
                $line["owner"] = $this->container->get("templating")->render("SgBlogBundle:Post:owner.html.twig", array(
                    "entity" => $repository->find($line["id"])
                ));
            }

            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatableView()
    {
        // the default settings, except "scroll_x"
        $this->features->setFeatures(array(
            "auto_width" => true,
            "defer_render" => false,
            "info" => true,
            "jquery_ui" => false,
            "length_change" => true,
            "ordering" => true,
            "paging" => true,
            "processing" => true,
            "scroll_x" => true,
            "scroll_y" => "",
            "searching" => true,
            "server_side" => true,
            "state_save" => false,
            "delay" => 0
        ));

        // the default settings, except "url"
        $this->ajax->setOptions(array(
            "url" => $this->container->get("router")->generate("post_results"),
            "type" => "GET"
        ));

        // the default settings, except "class", "individual_filtering_position" and "use_integration_options"
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
            "individual_filtering_position" => "both",
            "use_integration_options" => true
        ));

        $em = $this->container->get("doctrine")->getManager();
        $users = $em->getRepository("AppBundle:User")->findAll();

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
                "search_type" => "eq",     // will use eq operator in search query (for example "where visible = 1" etc.)
                "filter_type" => "select", // use select dropdown with options: any/yes/no options are automatically associated with "boolean" columntype
                "filter_options" => ["" => "Any", "1" => "Yes", "0" => "No"], // for server-side mode these keys should be equal to the values in the database
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
            ->add("owner", "virtual", array(
                'title' => "Owner"
            ))
            ->add("authorEmail", "column", array(
                "class" => "",
                "padding" => "",
                "name" => "",
                "orderable" => true,
                "render" => null,
                "searchable" => true,
                "filter_type" => "select", //  render the search input as a dropdown
                "filter_options" => ["" => "Any"] + $this->getCollectionAsOptionsArray($users, "email", "username"), // dropdown options list. This method should return all options as array [email => username]
                "filter_property" => "authorEmail", // You can set up another property, different with the current column, to search on.
                "title" => "<span class='glyphicon glyphicon-user' aria-hidden='true'></span> Author",
                "type" => "",
                "visible" => true,
                "width" => "",
                "default" => ""
            ))
            ->add("comments.title", "array", array(
                "title" => "Kommentare",
                "searchable" => false,
                "orderable" => false,
                "default" => "default value",
                "data" => "comments[, ].title",
            ))
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

    // Callback example
    $function = function($qb)
    {
        $qb->andWhere("Comment_comments.id < 10");
    };

    // Add callback
    $datatable->addWhereBuilderCallback($function);

    return $datatable->getResponse();
}
```

## Client-Side example

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

        $this->columnBuilder
            ->add("visible", "boolean", array(
                "class" => "",
                "padding" => "",
                "name" => "",
                "orderable" => true,
                "render" => "render_boolean",
                "searchable" => true,
                "search_type" => "eq",     // will use eq operator in search query (for example "where visible = 1" etc.)
                "filter_type" => "select", // use select dropdown with options: any/yes/no options are automatically associated with "boolean" columntype
                "filter_options" => ["" => "Any", "yes" => "Yes", "no" => "No"], // for client-side mode options keys should be equal to the values actually showed on the table
                "title" => "Visible",
                "type" => "",
                "visible" => true,
                "width" => "",
                "true_icon" => "glyphicon glyphicon-ok",
                "false_icon" => "",
                "true_label" => "yes",
                "false_label" => "no"
            ))
            // ...
        ));

        // ...
    }
}
```

### The controller action

```php
/**
 * Client side Post datatable.
 *
 * @Route("/cs", name="cs_post")
 * @Method("GET")
 * @Template(":post:index.html.twig")
 *
 * @return array
 */
public function clientSideIndexAction()
{
    $repository = $this->getDoctrine()->getRepository("AppBundle:Post");

    $query = $repository->createQueryBuilder("p")
        ->select("p, c")
        ->join("p.comments", "c")
        ->getQuery();

    $results = $query->getArrayResult();

    // the virtual field ...
    foreach ($results as $key => $value) {
        $results[$key]["owner"] = "test";
    }

    $encoders = array(new JsonEncoder());
    $normalizers = array(new GetSetMethodNormalizer());
    $serializer = new Serializer($normalizers, $encoders);

    $datatable = $this->get("app.datatable.client_side.post");
    $datatable->setData($serializer->serialize($results, "json"));

    return array(
        "datatable" => $datatable,
    );
}
```
