# In-place editing

1. [Setup](#1-setup)
2. [Text Editable](#2-text-editable)
3. [Textarea Editable](#3-textarea-editable)
4. [Select Editable](#4-select-editable)
5. [Combodate Editable](#5-combodate-editable)

## 1. Setup

### routing.yml

Adding this configuration to the app/config/routing.yml file:

``` yml
# app/config/routing.yml
sg_datatables_bundle:
    resource: "@SgDatatablesBundle/Controller/"
    type:     annotation
```

### X-editable js and css files

This bundle uses [X-editable](https://github.com/vitalets/x-editable) for in-place editing.

Ensure to load all js and css files from [X-editable](https://vitalets.github.io/x-editable/index.html) with your base layout.

**Example:**

``` html
{% block stylesheets %}
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/v/bs/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/b-1.2.2/b-colvis-1.2.2/b-flash-1.2.2/b-html5-1.2.2/b-print-1.2.2/fc-3.2.2/fh-3.1.2/r-2.1.0/datatables.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.25/daterangepicker.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/css/bootstrap-editable.css">
{% endblock %}
{% block head_javascripts %}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.15.1/moment-with-locales.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-daterangepicker/2.1.25/daterangepicker.min.js" charset="UTF-8"></script>
    <script src="https://cdn.datatables.net/v/bs/jszip-2.5.0/pdfmake-0.1.18/dt-1.10.12/b-1.2.2/b-colvis-1.2.2/b-flash-1.2.2/b-html5-1.2.2/b-print-1.2.2/fc-3.2.2/fh-3.1.2/r-2.1.0/datatables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.1/bootstrap3-editable/js/bootstrap-editable.min.js"></script>

    <script src="{{ asset('bundles/sgdatatables/js/pipeline.js') }}"></script>

    <script src="{{ asset('bundles/fosjsrouting/js/router.js') }}"></script>
    <script src="{{ path('fos_js_routing_js', {'callback': 'fos.Router.setData'}) }}"></script>
{% endblock %}
```
__

## 2. Text editable

### Template

SgDatatablesBundle:column:column_post_create_dt.js.twig

### Options

| Option        | Type            | Default              | Description    |
|---------------|-----------------|----------------------|----------------|
| url           | string          | 'sg_datatables_edit' | Url for submit. |
| params        | array or null   | null                 | Additional params for submit It is appended to original ajax data (pk, name and value). |
| default_value | string or null  | null                 | Value that will be displayed in input if original field value is empty. |
| empty_class   | string          | 'editable-empty'     | Css class applied when editable text is empty. |
| empty_text    | string          | 'Empty'              | Text shown when element is empty. |
| highlight     | string          | '#FFFF80'            | Color used to highlight element after update. |
| mode          | string          | 'popup'              | Mode of editable, can be 'popup' or 'inline'. |
| name          | string or null  | null                 | Name of field. Will be submitted on server. Can be taken from id attribute. |
| pk            | string          | 'id'                 | Primary key of editable object. |
| editable_if   | Closure or null | null                 | Editable only if conditions are True. |
| clear         | bool            | true                 | Whether to show clear button. |
| placeholder   | string or null  | null                 | Placeholder attribute of input. Shown when input is empty. |

### Example

``` php
$this->columnBuilder
    ->add('title', Column::class, array(
        'title' => 'Title',
        'searchable' => true,
        'orderable' => true,
        'editable' => array(TextEditable::class, array(
            //'pk' => 'cid',
            'placeholder' => 'Edit value',
            'empty_text' => 'Empty Text'
        ))
    ))
;
```
__

## 3. Textarea editable

### Template

SgDatatablesBundle:column:column_post_create_dt.js.twig

### Options

All options of [Text Editable](#2-text-editable).

**Additional:**

| Option | Type | Default | Description                 |
|--------|------|---------|-----------------------------|
| rows   | int  | 7       | Number of rows in textarea. |

### Example

``` php
$this->columnBuilder
    ->add('content', Column::class, array(
        'title' => 'Content',
        'editable' => array(TextareaEditable::class, array(
            //'pk' => 'cid',
            'rows' => 50
        ))
    ))
;
```
__

## 4. Select editable

### Template

SgDatatablesBundle:column:column_post_create_dt.js.twig

### Options

All options of [Text Editable](#2-text-editable).

**Additional:**

| Option | Type  | Default | Required | Description          |
|--------|-------|---------|----------|----------------------|
| source | array |         | x        |Source data for list. |

### Example

``` php
$this->columnBuilder
    ->add('visible', BooleanColumn::class, array(
        'title' => 'Visible',
        'true_label' => 'Yes',
        'false_label' => 'No',
        'true_icon' => 'glyphicon glyphicon-ok',
        'false_icon' => 'glyphicon glyphicon-remove',
        'filter' => array(SelectFilter::class, array(
            'classes' => 'test1 test2',
            'search_type' => 'eq',
            'multiple' => true,
            'select_options' => array(
                '' => 'Any',
                '1' => 'Yes',
                '0' => 'No'
            ),
            'cancel_button' => true,
        )),
        'editable' => array(SelectEditable::class, array(
            'editable_if' => function($row) {
                return $row['cid'] == 5;
            },
            'source' => array(
                array('value' => 1, 'text' => 'Yes'),
                array('value' => 0, 'text' => 'No'),
                array('value' => null, 'text' => 'Null')
            ),
            'mode' => 'inline',
            'empty_text' => '',
            //'pk' => 'cid',
            /*
            'params' => array(
                'dataStr' => 'test1',
            )
            */
        )),
    ))
;
```
__

## 5. Combodate editable

### Template

SgDatatablesBundle:column:column_post_create_dt.js.twig

### Options

All options of [Text Editable](#2-text-editable).

**Additional:**

| Option      | Type           | Default               | Description                 |
|-------------|----------------|-----------------------|-----------------------------|
| format      | string         | 'YYYY-MM-DD'          | Format used for sending value to server. |
| view_format | string or null | null (same as format) | Format used for displaying date. If not specified equals to $format. |
| min_year    | int            | 1970                  | Minimum value in years dropdown. |
| max_year    | int            | 2035                  | Maximum value in years dropdown. |
| minute_step | int            | 5                     | Step of values in minutes dropdown. |
| second_step | int            | 1                     | Step of values in seconds dropdown. |
| smart_days  | bool           | false                 | Number of days in dropdown is always 31 or not. |

### Example

``` php
$this->columnBuilder
    ->add('publishedAt', DateTimeColumn::class, array(
        'title' => 'Published at',
        'default_content' => 'No value',
        'date_format' => 'L',
        'filter' => array(DateRangeFilter::class, array(
            'cancel_button' => true,
        )),
        'editable' => array(CombodateEditable::class, array(
            'format' => 'YYYY-MM-DD',
            'view_format' => 'DD.MM.YYYY',
            'max_year' => 2025,
            //'pk' => 'cid',
        )),
        'timeago' => true
    ))
;
```
__
