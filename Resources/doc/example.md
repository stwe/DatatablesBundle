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

namespace Sg\BlogBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;

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
        //-------------------------------------------------
        // Datatable
        //-------------------------------------------------

        // Features (defaults)
        $this->getFeatures()
            ->setAutoWidth(true)
            ->setDeferRender(false)
            ->setInfo(true)
            ->setJQueryUI(false)
            ->setLengthChange(true)
            ->setOrdering(true)
            ->setPaging(true)
            ->setProcessing(true)  // default: false
            ->setScrollX(true)     // default: false
            ->setScrollY("")
            ->setSearching(true)
            ->setServerSide(true)  // default: false
            ->setStateSave(false)
            ->setDelay(500);       // default: 0

        // Options (for more options see file: Sg\DatatablesBundle\Datatable\View\Options.php)
        //$this->getOptions()->setLengthMenu(array(10, 25, 50));
        $this->getOptions()
            ->setLengthMenu(array(10, 25, 50, 100, -1))
            ->setOrder(array("column" => 1, "direction" => "desc"))
            ->setPagingType("simple_numbers");
            //->setResponsive(true); // enable Responsive extension

        $this->getAjax()->setUrl($this->getRouter()->generate("post_results"));

        $this->setStyle(self::BOOTSTRAP_3_STYLE);

        $this->setIndividualFiltering(true);


        //-------------------------------------------------
        // Columns
        //-------------------------------------------------

        $this->getColumnBuilder()
            ->add(null, "multiselect", array(
                    "start_html" => '<div class="wrapper" id="testwrapper">',
                    "end_html" => '</div>',
                    "attributes" => array(
                        "class" => "testclass",
                        "name" => "testname",
                    ),
                    "actions" => array(
                        array(
                            "route" => "post_bulk_delete",
                            "label" => "Delete",
                            "role" => "ROLE_ADMIN"
                        ),
                        array(
                            "route" => "post_bulk_disable",
                            "label" => "Disable"
                        )
                    )
                ))
            ->add("id", "column", array(
                    "title" => "Post-id",
                    "searchable" => true,
                    "orderable" => true,
                    "visible" => true,
                    "class" => "active",
                    "width" => "100px"
                ))
            ->add("createdBy.username", "column", array(
                    "title" => "Created by"
                ))
            ->add("updatedBy.username", "column", array(
                    "title" => "Updated by"
                ))
            ->add("title", "column", array(
                    "title" => $this->getTranslator()->trans("test.title", array(), "msg")
                ))
            ->add("visible", "boolean", array(
                    "title" => "Visible",
                    "true_label" => "yes",
                    "false_label" => "no",
                    "true_icon" => "glyphicon glyphicon-ok",
                    "false_icon" => "glyphicon glyphicon-remove"
                ))
            ->add("createdAt", /*choose timeago or datetime*/ "datetime", array(
                    "title" => "Created at"
                ))
            ->add("tags.name", "array", array(
                    "title" => "Tags",
                    "data" => "tags[, ].name"
                ))
            ->add(null, "action", array(
                "title" => "Actions",
                "start_html" => '<div class="wrapper">',
                "end_html" => '</div>',
                "actions" => array(
                    array(
                        "route" => "post_edit",
                        "route_parameters" => array(
                            "id" => "id"
                        ),
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
                        "renderif" => array(
                            "visible"
                        )
                    ),
                    array(
                        "route" => "post_show",
                        "route_parameters" => array(
                            "id" => "id"
                        ),
                        "label" => "Show",
                        "attributes" => array(
                            "rel" => "tooltip",
                            "title" => "Show",
                            "class" => "btn btn-default btn-xs",
                            "role" => "button"
                        ),
                        //"role" => "ROLE_USER",
                        //"renderif" => array("visible")
                    )
                )
            ));
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return "SgBlogBundle:Post";
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
{% extends 'SgBlogBundle::layout.html.twig' %}

{% block content_content %}
    {{ datatable_render(datatable) }}
{% endblock %}
```

#### Decouple html and js

```html
{% extends 'SgBlogBundle::layout.html.twig' %}

{% block content_content %}
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
        class: Sg\BlogBundle\Datatables\PostDatatable
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
 * @Template()
 *
 * @return array
 */
public function indexAction()
{
    $postDatatable = $this->get("sg_datatables.post");
    $postDatatable->buildDatatableView();

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
        $qb->andWhere("Post.visible = true");
    };

    // Add callback
    $datatable->addWhereBuilderCallback($function);

    return $datatable->getResponse();
}

/**
 * @Route("/bulk/delete", name="post_bulk_delete")
 * @Method("POST")
 *
 * @return Response
 */
public function bulkDeleteAction()
{
    $request = $this->getRequest();
    $isAjax = $request->isXmlHttpRequest();

    if ($isAjax) {
        $choices = $request->request->get("data");

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("SgBlogBundle:Post");

        foreach ($choices as $choice) {
            $entity = $repository->find($choice["value"]);
            $em->remove($entity);
        }

        $em->flush();

        return new Response("This is ajax response.");
    }

    return new Response("This is not ajax.", 400);
}

/**
 * @Route("/bulk/disable", name="post_bulk_disable")
 * @Method("POST")
 *
 * @return Response
 */
public function bulkDisableAction()
{
    $request = $this->getRequest();
    $isAjax = $request->isXmlHttpRequest();

    if ($isAjax) {
        $choices = $request->request->get("data");

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("SgBlogBundle:Post");

        foreach ($choices as $choice) {
            $entity = $repository->find($choice["value"]);
            $entity->setVisible(false);
            $em->persist($entity);
        }

        $em->flush();

        return new Response("This is ajax response.");
    }

    return new Response("This is not ajax.", 400);
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
        $this->getFeatures()->setServerSide(false);

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
    $postDatatable->buildDatatableView();
    $postDatatable->setData($serializer->serialize($results, 'json'));

    return array(
        'datatable' => $postDatatable,
    );
}
```
