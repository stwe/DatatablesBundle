# SgDatatablesBundle

[![SensioLabsInsight](https://insight.sensiolabs.com/projects/61803d08-17ab-4a69-ad13-6ec448762332/big.png)](https://insight.sensiolabs.com/projects/61803d08-17ab-4a69-ad13-6ec448762332)

[![knpbundles.com](http://knpbundles.com/stwe/DatatablesBundle/badge)](http://knpbundles.com/stwe/DatatablesBundle)

[![Latest Stable Version](https://poser.pugx.org/sg/datatablesbundle/v/stable)](https://packagist.org/packages/sg/datatablesbundle) [![Total Downloads](https://poser.pugx.org/sg/datatablesbundle/downloads)](https://packagist.org/packages/sg/datatablesbundle) [![Latest Unstable Version](https://poser.pugx.org/sg/datatablesbundle/v/unstable)](https://packagist.org/packages/sg/datatablesbundle) [![License](https://poser.pugx.org/sg/datatablesbundle/license)](https://packagist.org/packages/sg/datatablesbundle)

## Documentation

### master (unstable)

- New config style.

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

- Automatically call buildDatatableView()

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

- [#103](https://github.com/stwe/DatatablesBundle/pull/103) [mennowame](https://github.com/mennowame) Dutch translation
- [#104](https://github.com/stwe/DatatablesBundle/pull/104) [mdhheydari](https://github.com/mdhheydari) Persian Translation
- [#85](https://github.com/stwe/DatatablesBundle/issues/85) Use datatable with Responsive extension
- [#56](https://github.com/stwe/DatatablesBundle/issues/56) Custom Column Types

You can now use your custom column type, simply by creating a new instance of the type:

``` php
$this->columnBuilder
    ->add("title", new CustomColumn(), array(
            "example_string_option" => "title",
            "example_boolean_option" => false
        ));
```

- New: Multiselect is a column type:

``` php
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
    ));
```

[Read the Documentation for master](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/index.md).

[The examples for master](https://github.com/stwe/DatatablesBundle/blob/master/Resources/doc/example.md)

### v0.6.1 (recommended)

- [#79](https://github.com/stwe/DatatablesBundle/issues/79) Decoupling JS from HTML - functions have been forgotten

[Read the Documentation for v0.6.1](https://github.com/stwe/DatatablesBundle/blob/v0.6.1/Resources/doc/index.md).

[The examples for v0.6.1](https://github.com/stwe/DatatablesBundle/blob/v0.6.1/Resources/doc/example.md)

## Installation

All the installation instructions are located in the documentation.

## Friendly License

This bundle is available under the MIT license. See the complete license in the bundle:

    Resources/meta/LICENSE

You are free to use, modify and distribute this software, as long as the copyright header is left intact (specifically the comment block which starts with /*)!

## Reporting an issue or a feature request

Issues and feature requests are tracked in the [Github issue tracker](https://github.com/stwe/DatatablesBundle/issues).
