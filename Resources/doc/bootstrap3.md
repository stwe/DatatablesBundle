# Integrate Bootstrap3

## 1. Assets

The fastest way to get all needed files is to use the [download builder](https://www.datatables.net/download/) of the Datatables vendor. 

Example:

```html
<head>
    <meta charset="UTF-8" />
    <title>{% block title %}SgDatatablesBundleDemo{% endblock %}</title>
    {% block stylesheets %}
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.20/daterangepicker.min.css">
        <link rel="stylesheet" href="https://cdn.datatables.net/u/bs/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.12,b-1.2.1,b-colvis-1.2.1,b-flash-1.2.1,b-html5-1.2.1,b-print-1.2.1,r-2.1.0/datatables.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/featherlight/1.4.1/featherlight.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.1.0/css/bootstrap-slider.min.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" />
    {% endblock %}
    {% block head_javascripts %}
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.13.0/moment-with-locales.min.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.20/daterangepicker.min.js"></script>
        <script src="https://cdn.datatables.net/u/bs/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.12,b-1.2.1,b-colvis-1.2.1,b-flash-1.2.1,b-html5-1.2.1,b-print-1.2.1,r-2.1.0/datatables.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/featherlight/1.4.1/featherlight.min.js"></script>
        <script src="https://raw.githubusercontent.com/bartaz/sandbox.js/master/jquery.highlight.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-slider/7.1.0/bootstrap-slider.min.js"></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.min.js'></script>
        <script src="{{ asset('bundles/fosjsrouting/js/router.js') }}"></script>
        <script src="{{ path('fos_js_routing_js', {'callback': 'fos.Router.setData'}) }}"></script>
    {% endblock %}
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}" />
</head>
```

## 2. Datatable class

```php
class PostDatatable extends AbstractDatatableView
{
    public function buildDatatable(array $options = array())
    {
        // ...

        $this->options->set(array(
            // ...
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            'class' => Style::BOOTSTRAP_3_STYLE, // or Style::BOOTSTRAP_3_STYLE . ' table-condensed'
            'use_integration_options' => true
        ));

        $this->columnBuilder
            ->add('id', 'column', array(
                'title' => 'Id',
            ))
            ->add('title', 'column', array(
                'title' => 'Title',
            ))
            
            // ...
        ;
    }
```

## 3. Custom DOM model

You can define your own DOM model, following the syntax given on https://datatables.net/reference/option/dom
To do so, set the 'dom' and 'force_dom' options in your Datatable class.
'force_dom' is needed if you use integration options, to allow you to ovveride bootstrap's dom

```php
class PostDatatable extends AbstractDatatableView
{
    public function buildDatatable(array $options = array())
    {
        // ...

        $this->options->set(array(
            // ...
            // default bootstrap dom for datatable. modify it to your needs
            'dom' => "<'row'<'col-sm-6'l><'col-sm-6'f>>" .
                    "<'row'<'col-sm-12'tr>>" .
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            'force_dom' => true,
        ));

        // ...
    }
```
