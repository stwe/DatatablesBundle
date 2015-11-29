# Integrate Bootstrap3

## 1. Assets (CSS, JavaScript and image files)

The fastest way to get all needed files is to use the [download builder](https://www.datatables.net/download/) of the Datatables vendor. 

Example:

```html
<head>
    <meta charset="UTF-8" />
    <title>{% block title %}Example{% endblock %}</title>
    {% block stylesheets %}
        <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
        <link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" >
        <link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker-bs3.css" />
        <link rel="stylesheet" href="https://cdn.datatables.net/s/bs-3.3.5/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.10,b-1.1.0,b-colvis-1.1.0,b-flash-1.1.0,b-html5-1.1.0,b-print-1.1.0,r-2.0.0/datatables.min.css"/>
    {% endblock %}
    {% block head_javascripts %}
        <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/moment.js/2.10.6/moment-with-locales.min.js"></script>
        <script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
        <script src="//cdn.jsdelivr.net/bootstrap.daterangepicker/1/daterangepicker.js"></script>
        <script src="https://cdn.datatables.net/s/bs-3.3.5/jszip-2.5.0,pdfmake-0.1.18,dt-1.10.10,b-1.1.0,b-colvis-1.1.0,b-flash-1.1.0,b-html5-1.1.0,b-print-1.1.0,r-2.0.0/datatables.min.js"></script>
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
    public function buildDatatable($locale = null)
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
    public function buildDatatable($locale = null)
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
