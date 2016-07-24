# In-place editing

In-place editing for text, datetime and boolean fields.

<div style="text-align:center"><img alt="Screenshot" src="https://github.com/stwe/DatatablesBundle/raw/master/Resources/images/editable.jpg"></div>

## 1. Setup in-place editing

### routing.yml

```yaml
datatable:
    resource: "@SgDatatablesBundle/Controller/"
    type:     annotation
```

### X-editable js and css files

This bundle uses [X-editable](https://github.com/vitalets/x-editable) for in-place editing.

Ensure to load all js and css files from [X-editable files](https://vitalets.github.io/x-editable/index.html) with your base layout.

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

## 2. Enable or disable editing

Use the `editable` option.

```php
$this->columnBuilder
    ->add('title', 'column', array(
        'title' => 'Title',
        'editable' => true,
        'editable_if' => function($row) {
            return (
                $this->authorizationChecker->isGranted('ROLE_USER') &&
                $row['public'] == true
            );
        }
    ))
    ->add('publishedAt', 'datetime', array(
        'title' => 'Published at',
        'name' => 'daterange',
        'editable' => true,
        'date_format' => 'll'
    ))
    ->add('visible', 'boolean', array(
        'title' => 'Visible',
        'editable' => true
    ))

    // ...
;
```
