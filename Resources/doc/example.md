# An example

## Step 1: Create your layout.html.twig

```html
{% extends '::base.html.twig' %}

{% block title %}UserBundle{% endblock %}

{% block stylesheets %}

    {% stylesheets
        '@bootstrap_css'
        'bundles/sgdatatables/css/dataTables_bootstrap.css'
        filter='cssrewrite' %}
        <link href="{{ asset_url }}" rel="stylesheet" type="text/css"/>
    {% endstylesheets %}

{% endblock %}

{% block body%}

    {% block scripts %}

        {% javascripts
            '@jquery_js'
            '@bootstrap_js'
            '@datatables_js'
            'bundles/sgdatatables/js/dataTables_bootstrap.js'
            'bundles/sgdatatables/js/jquery.dataTables.columnFilter.js'
            'bundles/sgdatatables/js/moment-with-langs.min.js'
            'bundles/fosjsrouting/js/router.js' %}
            <script type="text/javascript" src="{{ asset_url }}"></script>
        {% endjavascripts %}

        <script src="{{ path('fos_js_routing_js', {"callback": "fos.Router.setData"}) }}"></script>

    {% endblock %}

    <div class="container">
        {% block content %}
        {% endblock %}
    </div>

{% endblock %}
```

## Step 2: Create your Datatables class

```php
<?php

namespace Sg\UserBundle\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatableView;
use Sg\DatatablesBundle\Column\ActionColumn;

/**
 * Class UserDatatable
 *
 * @package Sg\UserBundle\Datatables
 */
class UserDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function build()
    {
        //-------------------------------------------------
        // Datatable config
        //-------------------------------------------------

        $this->setTableId('user_datatable');
        $this->setSAjaxSource('user_results');

        $this->setMultiselect(true);
        $this->addBulkAction('Disable', 'user_bulk_disable');
        $this->addBulkAction('Delete', 'user_bulk_delete');

        $this->setIndividualFiltering(true);


        //-------------------------------------------------
        // Columns config
        //-------------------------------------------------

        $this->columnBuilder
            ->add('id', 'column', array(
                    'title' => 'Id',
                    'searchable' => false
                ))
            ->add('username', 'column', array(
                    'title' => 'Username',
                    'searchable' => false
                ))
            ->add('lastLogin', 'datetime', array(
                    'title' => 'Last Login'
                ))
            ->add('enabled', 'boolean', array(
                    'title' => 'Enabled',
                    'searchable' => false,
                    'width' => '90'
                ))
            ->add('posts.title', 'array', array(
                    'title' => 'Posts'
                ))
            ->add('comments.title', 'array', array(
                    'title' => 'Comments'
                ))
            ->add('edit', 'action', array(
                    'route' => 'sg_user_edit',
                    'parameters' => array(
                        'id' => 'id'
                    ),
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => 'Edit User'
                    ),
                    'icon' => ActionColumn::DEFAULT_EDIT_ICON
                ))
            ->add('show', 'action', array(
                    'route' => 'sg_user_show',
                    'parameters' => array(
                        'id' => 'id'
                    ),
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => 'Show User'
                    ),
                    'icon' => ActionColumn::DEFAULT_SHOW_ICON
                ));
    }
}
```

## Step 3: Create your index.html.twig

```html
{% extends 'SgUserBundle::layout.html.twig' %}

{% block title %}{{ title }}{% endblock %}

{% block content %}

    <h2>{{ title }}</h2>

    {{ datatable_render(datatable) }}

{% endblock %}
```

## Step 4: Add controller actions

```php
/**
 * Lists all User entities.
 *
 * @Route("/", name="user")
 * @Method("GET")
 * @Template()
 *
 * @return array
 */
public function indexAction()
{
    /**
     * @var \Sg\DatatablesBundle\Factory\DatatableFactory $factory
     */
    $factory = $this->get('sg_datatables.factory');

    /**
     * @var \Sg\DatatablesBundle\Datatable\AbstractDatatableView $datatableView
     */
    $datatableView = $factory->getDatatableView('Sg\UserBundle\Datatables\UserDatatable');

    return array(
        'title' => 'Enabled Users',
        'datatable' => $datatableView,
    );
}

/**
 * Get all User entities.
 *
 * @Route("/results", name="user_results")
 *
 * @return \Symfony\Component\HttpFoundation\Response
 */
public function indexResultsAction()
{
    /**
     * @var \Sg\DatatablesBundle\Datatable\DatatableData $datatable
     */
    $datatable = $this->get('sg_datatables.datatable')->getDatatable('SgUserBundle:User');

    /**
     * @var \Doctrine\ORM\QueryBuilder $qb
     */
    $callbackFunction =

        function($qb)
        {
            $andExpr = $qb->expr()->andX();
            $andExpr->add($qb->expr()->eq('fos_user.enabled', '1'));
            $qb->andWhere($andExpr);
        };

    $datatable->addWhereBuilderCallback($callbackFunction);

    return $datatable->getResults();
}

/**
 * @Route("/bulk/delete", name="user_bulk_delete")
 * @Method("POST")
 *
 * @return Response
 */
public function deleteAction()
{
    $request = $this->getRequest();
    $isAjax = $request->isXmlHttpRequest();

    if ($isAjax) {
        $choices = $request->request->get('data');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SgUserBundle:User');

        foreach ($choices as $choice) {
            $entity = $repository->find($choice['value']);
            $em->remove($entity);
        }

        $em->flush();

        return new Response('This is ajax response.');
    }

    return new Response('This is not ajax.', 400);
}

/**
 * @Route("/bulk/disable", name="user_bulk_disable")
 * @Method("POST")
 *
 * @return Response
 */
public function disableAction()
{
    $request = $this->getRequest();
    $isAjax = $request->isXmlHttpRequest();

    if ($isAjax) {
        $choices = $request->request->get('data');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SgUserBundle:User');

        foreach ($choices as $choice) {
            $entity = $repository->find($choice['value']);
            $entity->setEnabled(false);
            $em->persist($entity);
        }

        $em->flush();

        return new Response('This is ajax response.');
    }

    return new Response('This is not ajax.', 400);
}
```