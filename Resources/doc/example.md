# Examples

## Server side example

### Step 1: Create your Datatables class

```php
<?php

namespace Sg\BlogBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\BootstrapDatatableTheme;

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

        $this->setBServerSide(true);                        // default
        $this->setSAjaxSource($this->getRouter()->generate('post_results'));
        $this->setBProcessing(true);                        // default
        $this->setIDisplayLength(10);                       // default
        $this->setIndividualFiltering(true);                // default = false

        $this->setTheme(BootstrapDatatableTheme::getTheme());
//        $this->setTheme(JqueryUiDatatableTheme::getTheme());
//        $this->setTheme(BaseDatatableTheme::getTheme());

        $this->setMultiselect(true);                        // default = false
        $this->addBulkAction('Hide', 'post_bulk_disable');
        $this->addBulkAction('Delete', 'post_bulk_delete');


        //-------------------------------------------------
        // Columns
        //-------------------------------------------------

        $this->columnBuilder
            ->add('id', 'column', array(
                    'title' => 'Id',
                    'searchable' => false
                ))
            ->add('title', 'column', array(
                    'searchable' => true,     // default
                    'sortable' => true,       // default
                    'visible' => true,        // default
//                    'title' => 'Title',     // default = null
                    'title' => $this->getTranslator()->trans('test.title', array(), 'msg'),
                    'render' => null,         // default
                    'class' => 'text-center', // default = ''
                    'default' => null,        // default
                    'width' => null           // default
                ))
            ->add('visible', 'boolean', array(
                    'title' => 'Visible',
                    'true_icon' => BootstrapDatatableTheme::DEFAULT_TRUE_ICON,
                    'false_icon' => BootstrapDatatableTheme::DEFAULT_FALSE_ICON,
                    'true_label' => 'yes',
                    'false_label' => 'no'
                ))
            ->add('createdAt', 'timeago', array(
                    'title' => 'Created'
                ))
//            ->add('createdAt', 'datetime', array(
//                    'title' => 'Created'
//                ))
            ->add('createdBy.username', 'column', array(
                    'title' => 'CreatedBy'
                ))
            ->add('updatedBy.username', 'column', array(
                    'title' => 'UpdatedBy'
                ))
            ->add('tags.name', 'array', array(
                    'title' => 'Tags'
                ))
            ->add(null, 'action', array(
                    'route' => 'post_edit',
                    'parameters' => array(
                        'id' => 'id'
                    ),
                    'icon' => BootstrapDatatableTheme::DEFAULT_EDIT_ICON,
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => 'Edit User',
                        'class' => 'btn btn-danger btn-xs'
                    ),
                ))
            ->add(null, 'action', array(
                    'route' => 'post_show',
                    'parameters' => array(
                        'id' => 'id'
                    ),
//                    'label' => 'Show',
                    'label' => $this->getTranslator()->trans('test.show', array(), 'msg'),
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => 'Show User',
                        'class' => 'btn btn-primary btn-xs'
                    )
                ));
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

```html
{% extends 'SgBlogBundle::layout.html.twig' %}

{% block content_content %}
    {{ datatable_render(datatable) }}
{% endblock %}
```

### Step 3: Add controller actions

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
    /**
     * @var \Sg\DatatablesBundle\Datatable\View\DatatableViewFactory $factory
     */
    $factory = $this->get('sg_datatables.datatable.view.factory');

    /**
     * @var \Sg\DatatablesBundle\Datatable\View\AbstractDatatableView $datatableView
     */
    $datatableView = $factory->createDatatableView('Sg\BlogBundle\Datatables\PostDatatable');

    return array(
        'datatable' => $datatableView,
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
    $datatable = $this->get('sg_datatables.datatable')->getDatatable('SgBlogBundle:Post');

    return $datatable->getResults();
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
        $choices = $request->request->get('data');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SgBlogBundle:Post');

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
        $choices = $request->request->get('data');

        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SgBlogBundle:Post');

        foreach ($choices as $choice) {
            $entity = $repository->find($choice['value']);
            $entity->setVisible(false);
            $em->persist($entity);
        }

        $em->flush();

        return new Response('This is ajax response.');
    }

    return new Response('This is not ajax.', 400);
}
```

## Non server side example

### Step 1: Create your Datatables class

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
        $this->setBServerSide(false);

        // ...
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

```html
{% extends 'SgBlogBundle::layout.html.twig' %}

{% block content_content %}
    {{ datatable_render(datatable) }}
{% endblock %}
```

### Step 3: Add controller actions

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
        ->select('p')
        ->getQuery();

    $results = $query->getArrayResult();

    $encoders = array(new JsonEncoder());
    $normalizers = array(new GetSetMethodNormalizer());
    $serializer = new Serializer($normalizers, $encoders);

    /**
     * @var \Sg\DatatablesBundle\Datatable\View\DatatableViewFactory $factory
     */
    $factory = $this->get('sg_datatables.datatable.view.factory');

    /**
     * @var \Sg\DatatablesBundle\Datatable\View\AbstractDatatableView $datatableView
     */
    $datatableView = $factory->createDatatableView('Sg\BlogBundle\Datatables\PostDatatable');
    $datatableView->setAaData($serializer->serialize($results, 'json'));

    return array(
        'datatable' => $datatableView,
    );
}
```