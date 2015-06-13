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
            $repository = $this->em->getRepository($this->getEntity());
            $entity = $repository->find($line['id']);

            // see if a User is logged in
            if ($this->authorizationChecker->isGranted('IS_AUTHENTICATED_FULLY')) {
                $user = $this->securityToken->getToken()->getUser();
                // is the given User the author of this Post?
                $line['owner'] = $entity->isAuthor($user); // render 'true' or 'false'
            } else {
                // render a twig template with login link
                $line['owner'] = $this->twig->render(':post:login_link.html.twig', array(
                    'entity' => $repository->find($line['id'])
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
        $this->features->setFeatures(array(
            'auto_width' => true,
            'defer_render' => false,
            'info' => true,
            'jquery_ui' => false,
            'length_change' => true,
            'ordering' => true,
            'paging' => true,
            'processing' => true,
            'scroll_x' => true,
            'scroll_y' => '',
            'searching' => true,
            'server_side' => true,
            'state_save' => false,
            'delay' => 0
        ));

        $this->ajax->setOptions(array(
            'url' => $this->router->generate('post_results'),
            'type' => 'GET'
        ));

        $this->options->setOptions(array(
            'display_start' => 0,
            'dom' => 'lfrtip', // default, but not used because 'use_integration_options' = true
            'length_menu' => array(10, 25, 50, 100),
            'order_classes' => true,
            'order' => [[0, 'asc']],
            'order_multi' => true,
            'page_length' => 10,
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            'renderer' => '', // default, but not used because 'use_integration_options' = true
            'scroll_collapse' => false,
            'search_delay' => 0,
            'state_duration' => 7200,
            'stripe_classes' => array(),
            'responsive' => false,
            'class' => Style::BOOTSTRAP_3_STYLE . ' table-condensed',
            'individual_filtering' => true,
            'individual_filtering_position' => 'both',
            'use_integration_options' => true
        ));

        $users = $this->em->getRepository('AppBundle:User')->findAll();

        $this->columnBuilder
            ->add(null, 'multiselect', array(
                'start_html' => '<div class="wrapper" id="wrapper">',
                'end_html' => '</div>',
                'attributes' => array(
                    'class' => 'testclass',
                    'name' => 'testname',
                ),
                'actions' => array(
                    array(
                        'route' => 'post_bulk_delete',
                        'label' => $this->translator->trans('dtbundle.post.actions.delete'),
                        'role' => 'ROLE_ADMIN',
                        'icon' => 'fa fa-times',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('dtbundle.post.actions.delete'),
                            'class' => 'btn btn-danger btn-xs',
                            'role' => 'button'
                        ),
                    ),
                    array(
                        'route' => 'post_bulk_invisible',
                        'label' => $this->translator->trans('dtbundle.post.actions.invisible'),
                        'icon' => 'fa fa-eye-slash',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('dtbundle.post.actions.invisible'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    )
                )
            ))
            ->add('id', 'column', array(
                'class' => '',
                'padding' => '',
                'name' => '',
                'orderable' => true,
                'render' => null,
                'searchable' => true,
                'title' => 'Id',
                'type' => '',
                'visible' => true,
                'width' => '40px',
                'default' => ''
            ))
            ->add('visible', 'boolean', array(
                'class' => '',
                'padding' => '',
                'name' => '',
                'orderable' => true,
                'render' => 'render_boolean',
                'searchable' => true,
                'title' => $this->translator->trans('dtbundle.post.titles.visible'),
                'type' => '',
                'visible' => true,
                'width' => '50px',
                'true_icon' => 'glyphicon glyphicon-ok',
                'false_icon' => '',
                'true_label' => 'yes',
                'false_label' => 'no',
                'search_type' => 'eq',
                'filter_type' => 'select',
                'filter_options' => ['' => $this->translator->trans('dtbundle.post.filter.any'), '1' => $this->translator->trans('dtbundle.post.filter.yes'), '0' => $this->translator->trans('dtbundle.post.filter.no')],
            ))
            ->add('publishedAt', 'datetime', array(
                'class' => '',
                'padding' => '',
                'name' => 'daterange',
                'orderable' => true,
                'render' => 'render_datetime',
                'date_format' => 'lll',
                'searchable' => true,
                'title' => "<span class='glyphicon glyphicon-calendar' aria-hidden='true'></span> " . $this->translator->trans('dtbundle.post.titles.published'),
                'type' => '',
                'visible' => true,
                'width' => '120px'
            ))
            ->add('title', 'column', array(
                'title' => "<span class='glyphicon glyphicon-book' aria-hidden='true'></span> " . $this->translator->trans('dtbundle.post.titles.title'),
                'width' => '120px',
            ))
            ->add('authorEmail', 'column', array(
                'class' => '',
                'padding' => '',
                'name' => '',
                'orderable' => true,
                'render' => null,
                'searchable' => true,
                'title' => "<span class='glyphicon glyphicon-user' aria-hidden='true'></span> " . $this->translator->trans('dtbundle.post.titles.email'),
                'type' => '',
                'visible' => true,
                'width' => '',
                'default' => '',
                'filter_type' => 'select',
                'filter_options' => ['' => $this->translator->trans('dtbundle.post.filter.any')] + $this->getCollectionAsOptionsArray($users, 'email', 'username'),
                'filter_property' => 'authorEmail',
            ))
            // Virtual column example
            ->add('owner', 'virtual', array(
                'title' => $this->translator->trans('dtbundle.post.titles.owner')
            ))
            // Association examples
            ->add('comments.title', 'array', array(
                'title' => $this->translator->trans('dtbundle.post.titles.comments'),
                'searchable' => true,
                'orderable' => true,
                'data' => 'comments[, ].title',
            ))
            ->add('comments.createdby.username', 'array', array(
                'title' => 'Created by',
                'searchable' => true,
                'orderable' => true,
                'data' => 'comments[, ].createdby.username',
            ))
            ->add('comments.updatedby.username', 'array', array(
                'title' => 'Updated by',
                'searchable' => true,
                'orderable' => true,
                'data' => 'comments[, ].updatedby.username',
            ))
            ->add(null, 'action', array(
                'title' => $this->translator->trans('dtbundle.post.titles.actions'),
                'start_html' => '<div class="wrapper">',
                'end_html' => '</div>',
                'actions' => array(
                    array(
                        'route' => 'post_show',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('dtbundle.post.actions.show'),
                        'icon' => 'glyphicon glyphicon-eye-open',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('dtbundle.post.actions.edit'),
                            'class' => 'btn btn-default btn-xs',
                            'role' => 'button'
                        ),
                        'role' => 'ROLE_USER',
                        'render_if' => array('visible')
                    ),
                    array(
                        'route' => 'post_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('dtbundle.post.actions.edit'),
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('dtbundle.post.actions.edit'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                        'confirm' => true,
                        'confirm_message' => 'Are you sure?',
                        'role' => 'ROLE_ADMIN',
                    )
                )
            ));
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
app.datatable.post:
    class: AppBundle\Datatables\PostDatatable
    tags:
        - { name: sg.datatable.view }
```

### Step 4: Add controller actions

```php
/**
 * Server side Post datatable.
 *
 * @Route("/", name="post")
 * @Method("GET")
 * @Template(":post:index.html.twig")
 *
 * @return array
 */
public function indexAction()
{
    $datatable = $this->get('app.datatable.post');

    return array(
        'datatable' => $datatable,
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
    $query = $this->get('sg_datatables.query')->getQueryFrom($this->get('app.datatable.post'));

    // Callback example
    $function = function($qb)
    {
        $qb->andWhere("post.visible = true");
    };

    // Add the callback function as WhereResult
    //$query->addWhereResult($function);

    // Or add the callback function as WhereAll
    //$query->addWhereAll($function);

    // Or to the actual query
    //$query->buildQuery();
    //$qb = $query->getQuery();
    //$qb->andWhere("post.visible = true");
    //$query->setQuery($qb);
    //return $query->getResponse(false);

    return $query->getResponse();
}

/**
 * Delete action.
 *
 * @param Request $request
 *
 * @Route("/bulk/delete", name="post_bulk_delete")
 * @Method("POST")
 * @Security("has_role('ROLE_ADMIN')")
 *
 * @return Response
 */
public function bulkDeleteAction(Request $request)
{
    $isAjax = $request->isXmlHttpRequest();

    if ($isAjax) {
        $choices = $request->request->get("data");

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("AppBundle:Post");

        foreach ($choices as $choice) {
            $entity = $repository->find($choice["value"]);
            $em->remove($entity);
        }

        $em->flush();

        return new Response("Success", 200);
    }

    return new Response("Bad Request", 400);
}

/**
 * Invisible action.
 *
 * @param Request $request
 *
 * @Route("/bulk/invisible", name="post_bulk_invisible")
 * @Method("POST")
 *
 * @return Response
 */
public function bulkInvisibleAction(Request $request)
{
    $isAjax = $request->isXmlHttpRequest();

    if ($isAjax) {
        $choices = $request->request->get("data");

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository("AppBundle:Post");

        foreach ($choices as $choice) {
            $entity = $repository->find($choice["value"]);
            $entity->setVisible(false);
            $em->persist($entity);
        }

        $em->flush();

        return new Response("Success", 200);
    }

    return new Response("Bad Request", 400);
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
            'server_side' => false
        ));

        $this->columnBuilder
            ->add('visible', 'boolean', array(
                'class' => '',
                'padding' => '',
                'name' => '',
                'orderable' => true,
                'render' => 'render_boolean',
                'searchable' => true,
                'search_type' => 'eq',     // will use eq operator in search query (for example 'where visible = 1' etc.)
                'filter_type' => 'select', // use select dropdown with options: any/yes/no options are automatically associated with 'boolean' columntype
                'filter_options' => ['' => 'Any', 'yes' => 'Yes', 'no' => 'No'], // for client-side mode options keys should be equal to the values actually showed on the table
                'title' => 'Visible',
                'type' => '',
                'visible' => true,
                'width' => '',
                'true_icon' => 'glyphicon glyphicon-ok',
                'false_icon' => '',
                'true_label' => 'yes',
                'false_label' => 'no'
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
 * @Route('/cs', name='cs_post')
 * @Method('GET')
 * @Template(':post:index.html.twig')
 *
 * @return array
 */
public function clientSideIndexAction()
{
    $repository = $this->getDoctrine()->getRepository('AppBundle:Post');

    $query = $repository->createQueryBuilder('p')
        ->select('p, c')
        ->join('p.comments', 'c')
        ->getQuery();

    $results = $query->getArrayResult();

    // the virtual field ...
    foreach ($results as $key => $value) {
        $results[$key]['owner'] = 'test';
    }

    $encoders = array(new JsonEncoder());
    $normalizers = array(new GetSetMethodNormalizer());
    $serializer = new Serializer($normalizers, $encoders);

    $datatable = $this->get('app.datatable.client_side.post');
    $datatable->setData($serializer->serialize($results, 'json'));

    return array(
        'datatable' => $datatable,
    );
}
```
