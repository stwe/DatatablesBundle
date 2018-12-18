# Extensions

1. [Buttons](#1-buttons)
2. [Responsive](#2-responsive)
3. [Select](#3-select)
4. [RowGroup](#4-rowgroup)

## 1. Buttons

**Be sure to install the [Buttons Extension](https://datatables.net/extensions/buttons/) before using.**

### Template

@SgDatatables/datatable/extensions.html.twig

### Initialisation

**For display the buttons somewhere on the page you must put the `B` character to the `dom` option:**

**Example:**

``` php
public function buildDatatable(array $options = array())
{
    // ...

    $this->options->set(array(
        // ...
        'dom' => 'Bfrtip',
    ));
    
    // ...
}
```

#### The easiest way

The easiest way is to add `buttons` to your extensions options with a boolean value.

``` php
public function buildDatatable(array $options = array())
{
    // ...

    $this->extensions->set(array(
        'buttons' => true,
    ));
    
    // ...
}
```

#### Advanced example

This example shows two built-in and two custom buttons.

``` php
public function buildDatatable(array $options = array())
{
    // ...

    $this->extensions->set(array(
        //'buttons' => true,
        'buttons' => array(
            'show_buttons' => array('copy', 'print'),    // built-in buttons
            'create_buttons' => array(                   // custom buttons 
                array(
                    'action' => array(
                        'template' => ':post:action.js.twig',
                        //'vars' => array('id' => '2', 'test' => 'new value'),
                    ),
                    'text' => 'alert',
                ),
                array(
                    'extend' => 'csv',
                    'text' => 'custom csv button',
                ),
                array(
                    'extend' => 'pdf',
                    'text' => 'my pdf',
                    'button_options' => array(
                        'exportOptions' => array(
                            'columns' => array('1', '2'),
                        ),
                    ),
                ),
            ),
        ),
    ));
    
    // ...
}
```

``` twig
{# action.js.twig #}

action = function (e, dt, node, config) {
    alert('Activated!');
    this.disable(); // disable the button
}
```

### Buttons class options

With the Responsive class you can set the `show_buttons` and the `create_buttons` options.

With the `create_buttons` option you can create custom buttons. Each button has the following properties:

| Option         | Type           | Default |  Description                       |
|----------------|----------------|---------|------------------------------------|
| action         | array or null  | null    | Function describing the action to take on activation. |
| available      | array or null  | null    | Ensure that any requirements have been satisfied before initialising a button. |
| class_name     | string or null | null    | Button class name. |
| destroy        | array or null  | null    | Function that is called when the button is destroyed. |
| enabled        | bool or null   | null    | Initial enabled state. |
| extend         | string or null | null    | Based extends object. |
| init           | array or null  | null    | Button initialisation callback function. |
| key            | string or null | null    | Key activation configuration. |
| name           | string or null | null    | Button name for use in selectors. |
| namespace      | string or null | null    | Unique namespace for every button. |
| text           | string or null | null    | Visible text. |
| title_attr     | string or null | null    | Button title attribute text. |
| button_options | array or null  | null    | All special button options. |
___

## 2. Responsive

**Be sure to install the [Responsive Extension](https://datatables.net/extensions/responsive/) before using.**

### Template

@SgDatatables/datatable/extensions.html.twig

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
}
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
}
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

## 3. Select
   
**Be sure to install the [Select Extension](https://datatables.net/extensions/select/) before using.**

### Template

@SgDatatables/datatable/extensions.html.twig

### Initialisation

#### The easiest way

The easiest way is to add `select` to your extensions options with a boolean value.

``` php
public function buildDatatable(array $options = array())
{
   // ...

   $this->extensions->set(array(
       'select' => true,
   ));
   
   // ...
}
```

#### Advanced example

The Bootstrap modal example:

``` php
public function buildDatatable(array $options = array())
{
   // ...

   $this->extensions->set(array(
       'select' => array(
           'blurable' => false,
           'className' => 'selected',
           'info' => true,
           'items' => 'row',
           'selector' => 'td, th',
           'style' => 'os',
       ),
   ));
   
   // ...
}
```

### Select class options

With the Select class you can set the following options, for details see the [Plugin documentation](https://datatables.net/reference/option/#select).

| Option    | Type            | Default |  Description                       |
|-----------|-----------------|---------|------------------------------------|
| blurable  | boolean or null | null    | Indicate if the selected items will be removed when clicking outside of the table |
| classname | string or null  | null    | Set the class name that will be applied to selected items |
| info      | boolean or null | null    | Enable / disable the display for item selection information in the table summary |
| items     | string or null  | null    | Set which table items to select (rows, columns or cells) |
| selector  | string or null  | null    | Set the element selector used for mouse event capture to select items |
| style     | string or null  | null    | Set the selection style for end user interaction with the table |
___

## 4. RowGroup

**Be sure to install the [RowGroup Extension](https://datatables.net/extensions/rowgroup/) before using.**

### Template

@SgDatatables/datatable/extensions.html.twig

### Initialisation

#### The easiest way

The easiest way is to add `row_group` to your extensions options with the column's name string value in the `data_src` option to define the data source to use for initial grouping.

``` php
public function buildDatatable(array $options = array())
{
    // ...

    $this->extensions->set(array(
        'row_group' => array(
            'data_src' => 'title'
        ),
    ));
    
    // ...
}
```

#### Advanced example

This example shows a custom start render:

``` php
public function buildDatatable(array $options = array())
{
    // ...

    $this->extensions->set(array(
        'row_group' => array(
            'data_src' => 'title',
            'start_render' => [
                'template' => ':extension/row_group_start_renderer.js.twig',
                //'vars' => array('id' => '2', 'test' => 'new value'),
            ]
        ),
    ));
    
    // ...
}
```


``` twig
{# row_group_start_renderer.js.twig #}

startRenderer = function (rows, group) {
    return $('<tr/>').append( '<td>Post title: '+group+'</td>' );
}
```

### RowGroup class options

With the RowGroup class you can set the following options, for details see the [Plugin documentation](https://datatables.net/reference/option/#rowgroup).

| Option    | Type            | Default |  Description                       |
|-----------|-----------------|---------|------------------------------------|
| dataSrc           | string or null  | null  | Set the data point to use as the grouping data source |
| enable            | boolean         | true  | Provides the ability to disable row grouping at initialisation |
| className         | array or null   | null  | Set the class name to be used for the grouping rows |
| emptyDataGroup    | string or null  | null  | Text to show for rows which have null or undefined group data |
| startClassName    | string or null  | null  | Set the class name to be used for the grouping start rows |
| endClassName      | string or null  | null  | Set the class name to be used for the grouping end rows |
| endRender         | array or null   | null  | Provide a function that can be used to control the data shown in the end grouping row. |
| startRender       | array or null   | null  | Provide a function that can be used to control the data shown in the start grouping row. |
___
