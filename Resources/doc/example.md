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
use Sg\DatatablesBundle\Column\Column;
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

        $nameField = new Column('username');
        $nameField->setSTitle('Username');
        $nameField->setBSearchable(false);

        $emailField = new Column('email');
        $emailField->setSTitle('Email');

        $enabledField = new Column('enabled');
        $enabledField->setSTitle('Enabled');
        $enabledField->setBSearchable(false);
        $enabledField->setSWidth('90');
        $enabledField->setMRender("render_boolean_icons");

        // simple one-to-many association
        $postsField = new Column('posts.title');
        $postsField->setSTitle('Posts');


        //-------------------------------------------------
        // ActionColumns config
        //-------------------------------------------------

        $editField = new ActionColumn();

        // set a title
        //$editField->setSTitle('Edit');

        // example edit route: @Route("/{id}/edit", name="user_edit", options={"expose"=true})
        // important here is the section: options={"expose"=true}
        $editField->setRoute('sg_calendar_edit_calendar');
        $editField->addRouteParameter('id', 'id');

        // you can set multiple parameters:
        //$editField->addRouteParameter('param1', 'email');
        //$editField->addRouteParameter('param2', 'username');

        // and add a Bootstrap2 tooltip
        $editField->addAttribute('rel', 'tooltip');
        $editField->addAttribute('title', 'Edit User');

        // set a label
        //$editField->setLabel('TestLabel');

        // ... or an icon
        $editField->setIcon(ActionColumn::DEFAULT_EDIT_ICON);

        $showField = new ActionColumn();
        $showField->setRoute('sg_calendar_edit_calendar');
        $showField->addRouteParameter('id', 'id');
        $showField->addAttribute('rel', 'tooltip');
        $showField->addAttribute('title', 'Show User');
        $showField->setIcon(ActionColumn::DEFAULT_SHOW_ICON);


        //-------------------------------------------------
        // All columns added
        //-------------------------------------------------

        $this->addColumn($nameField);
        $this->addColumn($emailField);
        $this->addColumn($enabledField);
        $this->addColumn($postsField);
        $this->addActionColumn($editField);
        $this->addActionColumn($showField);
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