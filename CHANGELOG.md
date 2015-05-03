# CHANGELOG

## master

Here is a list of the most important changes:

### New config style

``` php
    public function buildDatatableView()
    {
        $this->features->setFeatures(array(
            "processing" => true
        ));

        $this->ajax->setOptions(array(
            "url" => $this->getRouter()->generate('post_results')
        ));

        $this->options->setOptions(array(
            "paging_type" => Style::FULL_PAGINATION,
            "responsive" => true,
            "class" => Style::BASE_STYLE,
            "individual_filtering" => false
        ));

        // ...
    }
```

### Automatically call buildDatatableView()

Example:

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
    $postDatatable = $this->get("sg_datatables.post");

    return array(
        "datatable" => $postDatatable,
    );
}
```

### Dutch & Persian translations

- [#103](https://github.com/stwe/DatatablesBundle/pull/103) [mennowame](https://github.com/mennowame) Dutch translation
- [#104](https://github.com/stwe/DatatablesBundle/pull/104) [mdhheydari](https://github.com/mdhheydari) Persian Translation

### Responsive extension

- [#85](https://github.com/stwe/DatatablesBundle/issues/85) Use datatable with Responsive extension

### Custom column types

- [#56](https://github.com/stwe/DatatablesBundle/issues/56) Custom Column Types

You can now use your custom column type, simply by creating a new instance of the type:

``` php
$this->columnBuilder
    ->add("title", new CustomColumn(), array(
            "example_string_option" => "title",
            "example_boolean_option" => false
        ));
```

### Multiselect is a column type

``` php
$this->columnBuilder
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
    ));
```

