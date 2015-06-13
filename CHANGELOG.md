# CHANGELOG

## master

Here is a list of the most important changes:

### Remove DatatableData service ('sg_datatables.datatable'), use 'sg_datatables.query'

Example:

``` php
/**
 * Get response.
 *
 * @Route("/results", name="post_results")
 *
 * @return \Symfony\Component\HttpFoundation\Response
 */
public function indexResultsAction()
{
    $query = $this->get('sg_datatables.query')->getQueryFrom($this->get('app.datatable.post'));

    return $query->getResponse();
}
```

### Callbacks (WhereResult, WhereAll) && get query

/**
 * Get all Post entities.
 *
 * @Route("/results", name="post_results")
 *
 * @return \Symfony\Component\HttpFoundation\Response
 */
public function indexResultsAction()
{
    $query = $this->get('sg_datatables.query')->getQueryFrom($this->get('app.datatable.server_side.post'));

    // Callback example
    $function = function($qb)
    {
        $qb->andWhere("post.visible = true");
    };

    // Add the callback function as WhereResult
    //$query->addWhereResult($function);

    // Or add the callback function as WhereAll
    $query->addWhereAll($function);

    // Or get the actual query and do whatever you want with this
    //$query->buildQuery();
    //$qb = $query->getQuery()->getDQL();
    //var_dump($qb); die();
    //return $query->getResponse(false);

    return $query->getResponse();
}

### Custom filters

- [#29](https://github.com/stwe/DatatablesBundle/issues/29) [dchabanenko](https://github.com/dchabanenko)

``` php
$this->columnBuilder
    ->add('visible', 'boolean', array(
        'class' => "",
        'padding' => "",
        'name' => "",
        'orderable' => true,
        'render' => "render_boolean",
        'searchable' => true,
        'search_type' => 'eq', // will use eq operator in search query (for example "where visible = 1" etc.)
        'filter_type' => 'select', // use select dropdown with options: any/yes/no options are automatically associated with 'boolean' columntype
        'filter_options' => ['' => 'Any', '1' => 'Yes', '0' => 'No'], // For client-side mode options keys should be equal to the values actually showed on the table,
        //but for server-side mode these keys should be equal to the values in the database.
        'title" => 'Visible',
        'type" => '',
        'visible" => true,
        'width" => '40px',
        'true_icon" => 'glyphicon glyphicon-ok',
        'false_icon" => '',
        'true_label" => 'yes',
        'false_label" => 'no'
    ))
```

### Date range individual filtering and others

- [#111](https://github.com/stwe/DatatablesBundle/pull/111) [DarekTw](https://github.com/DarekTw)

### New config style

``` php
public function buildDatatableView()
{
    $this->features->setFeatures(array(
        'processing' => true
    ));

    $this->ajax->setOptions(array(
        'url' => $this->container->get('router')->generate('post_results')
    ));

    $this->options->setOptions(array(
        'paging_type' => Style::FULL_PAGINATION,
        'responsive' => true,
        'class' => Style::BASE_STYLE,
        'individual_filtering' => false
    ));

    // ...
}
```

### Automatically call buildDatatableView()

``` php
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
    $datatable = $this->get('app.datatable.post');

    return array(
        'datatable' => $datatable,
    );
}
```

### New translations

- [#103](https://github.com/stwe/DatatablesBundle/pull/103) [mennowame](https://github.com/mennowame) Dutch translation
- [#104](https://github.com/stwe/DatatablesBundle/pull/104) [mdhheydari](https://github.com/mdhheydari) Persian Translation
- [#142](https://github.com/stwe/DatatablesBundle/pull/142) [inmarelibero](https://github.com/inmarelibero) Italian Translation

### Responsive extension

- [#85](https://github.com/stwe/DatatablesBundle/issues/85) Use datatable with Responsive extension

### Custom column types

- [#56](https://github.com/stwe/DatatablesBundle/issues/56) Custom Column Types

You can now use your custom column type, simply by creating a new instance of the type:

``` php
$this->columnBuilder
    ->add("title", new CustomColumn(), array( /* ... */ ));
```

### Multiselect is a column type

``` php
$this->columnBuilder
    ->add(null, 'multiselect', array( /* ... */ ));
```

