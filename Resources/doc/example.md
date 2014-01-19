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

        $this->setTableId('post_datatable');
        $this->setSAjaxSource('post_results');

        $this->setMultiselect(false);
        $this->setIndividualFiltering(false);

        $this->theme = BootstrapDatatableTheme::getTheme();
        //$this->theme = JqueryUiDatatableTheme::getTheme();
        //$this->theme = BaseDatatableTheme::getTheme();


        //-------------------------------------------------
        // Columns
        //-------------------------------------------------

        $this->columnBuilder
            ->add('id', 'column', array(
                    'title' => 'Id',
                    'searchable' => false
                ))
            ->add('title', 'column', array(
                    //'title' => 'Title',
                    'title' => array('label' => 'test.title', 'translation_domain' => 'msg'),
                    'searchable' => true
                ))
            ->add('visible', 'boolean', array(
                    'title' => 'Visible'
                ))
            ->add('createdAt', 'datetime', array(
                    'title' => 'Created'
                ))
            ->add('edit', 'action', array(
                    'route' => 'post_edit',
                    'parameters' => array(
                        'id' => 'id'
                    ),
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => 'Edit User'
                    ),
                    'icon' => BootstrapDatatableTheme::DEFAULT_EDIT_ICON
                ))
            ->add('show', 'action', array(
                    'route' => 'post_show',
                    'parameters' => array(
                        'id' => 'id'
                    ),
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => 'Show User'
                    ),
                    //'label' => 'Show',
                    'label' => array('label' => 'test.show', 'translation_domain' => 'msg')
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
 * Lists all Post entities.
 *
 * @Route("/", name="post")
 * @Method("GET")
 * @Template()
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
        'title' => 'Enabled Users',
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
    /**
     * @var \Sg\DatatablesBundle\Datatable\DatatableData $datatable
     */
    $datatable = $this->get('sg_datatables.datatable')->getDatatable('SgBlogBundle:Post');

    return $datatable->getResults();
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