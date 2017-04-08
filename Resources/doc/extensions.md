# Extensions

1. [Buttons](#1-buttons)
2. [Responsive](#2-responsive)

## 1. Buttons

***!currently not implemented!***

### Template

SgDatatablesBundle:datatable:extensions.html.twig

### Initialisation

### Buttons class options

___

## 2. Responsive

**Be sure to install the [Responsive Extension](https://datatables.net/extensions/responsive/) before using.**

### Template

SgDatatablesBundle:datatable:extensions.html.twig

### Initialisation

#### The easiest way

The easiest way is to add `responsive` to your extensions options with a boolean value.

``` php
public function buildDatatable(array $options = array())
{
    // ...

    $this->extensions->set(array(
        'responsive' => true,
    ));
    
    // ...
```

#### Advanced example

The Bootstrap modal example:

``` php
public function buildDatatable(array $options = array())
{
    // ...

    $this->extensions->set(array(
        //'responsive' => true,
        'responsive' => array(
            //'details' => true,
            'details' => array(
                'display' => array(
                    'template' => ':post:display.js.twig',
                    //'vars' => array('id' => '2', 'test' => 'new value'),
                ),
                'renderer' => array(
                    'template' => ':post:renderer.js.twig',
                ),
            ),
        ),
    ));
    
    // ...
```

``` twig
{# display.js.twig #}

$.fn.dataTable.Responsive.display.modal({
    header: function (row) {
        var data = row.data();
        return 'Details for ' + data.title;
    }
})
```

``` twig
{# renderer.js.twig #}

renderer = function (api, rowIdx, columns) {
    var data = $.map(columns, function (col, i) {
        return col.hidden ?
            '<tr data-dt-row=' + col.rowIndex + ' data-dt-column=' + col.columnIndex + '>' +
            '<td>' + col.title + ':' + '</td> ' +
            '<td>' + col.data + '</td>' +
            '</tr>' : '';
    }).join('');

    return data ? $('<table/>').append( data ) : false;
}
```

### Responsive class options

With the Responsive class you can set some `display` options to define how the hidden data should be displayed.

| Option   | Type                | Default |  Description                       |
|----------|---------------------|---------|------------------------------------|
| type     | string or null      | null    | Set the child row display control type. |
| target   | string, int or null | null    | Column / selector for child row display control. |
| renderer | array or null       | null    | Define the renderer used to display the child rows.   |
| display  | array or null       | null    | Define how the hidden information should be displayed to the end user. |

___
