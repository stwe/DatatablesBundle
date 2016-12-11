# Columns

1. [Column](#1-column)
2. [Boolean Column](#2-boolean-column)
3. [Virtual Column](#3-virtual-column)
4. [Action Column](#4-action-column)
5. [Multiselect Column](#5-multiselect-column)

## 1. Column

Represents the most basic column.

### Default template

SgDatatablesBundle:column:column.html.twig

### Options

With 'null' initialized options uses the default value of the DataTables plugin.

| Option          | Type               | Default           | Required | Description |
|-----------------|--------------------|-------------------|----------|-------------|
| cell_type       | null or string     | null              |          | Cell type to be created for a column. |
| class_name      | null or string     | null              |          | Class to assign to each cell in the column. |
| content_padding | null or string     | null              |          | Add padding to the text content used when calculating the optimal with for a table. |
| data            | null or string     |                   |          | The data source for the column. |
| default_content | null or string     | null              |          | Set default, static, content for a column. |
| name            | null or string     | null              |          | Set a descriptive name for a column. |
| orderable       | bool               | true              |          | Enable or disable ordering on this column. |
| order_data      | null, array or int | null              |          | Define multiple column ordering as the default order for a column. |
| order_sequence  | null or array      | null              |          | Order direction application sequence. |
| searchable      | bool               | true              |          | Enable or disable filtering on the data in this column. |
| title           | null or string     | null              |          | Set the column title. |
| visible         | bool               | true              |          | Enable or disable the display of this column. |
| width           | null or string     | null              |          | Column width assignment. |
| add_if          | null or Closure    | null              |          | Add column only if conditions are TRUE. |
| join_type       | string             | 'leftJoin'        |          | Join type (default: 'leftJoin'), if the column represents an association. |
| type_of_field   | null or string     | null (autodetect) |          | Set the data type itself for ordering (example: integer instead string). |
| filter          | array              | TextFilter        |          | A Filter instance for individual filtering. |
| editable        | bool               | false             |          | Enable edit mode for this column. |
| editable_if     | null or Closure    | null              |          | Enable edit mode for this column only if conditions are TRUE. |


### Example

``` php
$this->columnBuilder
    ->add('title', Column::class, array(
        'title' => 'title',
        'searchable' => true, // default
        'orderable' => true, // default
        'type_of_field' => 'integer' // Example: If numbers are stored as a string, try this option for a better ordering.
        'add_if' => function() {
            return ($this->authorizationChecker->isGranted('ROLE_ADMIN'));
        },
    ))
;
```

**For many-to-one associations:**

``` php
$this->columnBuilder
    ->add('createdBy.username', Column::class, array(
        'title' => 'CreatedBy'
    ))
    ->add('updatedBy.username', Column::class, array(
        'title' => 'UpdatedBy'
    ))
;
```

**For one-to-many associations:**


___

## 2. Boolean column

Represents a column, optimized for boolean values.

### Default template

SgDatatablesBundle:column:column.html.twig

### Options

All options of [Column](#1-column).

**Additional:**

| Option      | Type               | Default      | Required | Description                                 |
|-------------|--------------------|--------------|----------|---------------------------------------------|
| filter      | array              | SelectFilter |          | A Filter instance for individual filtering. |
| true_icon   | null or string     | null         |          | Icon for true                               |
| false_icon  | null or string     | null         |          | Icon for false                              |
| true_label  | null or string     | null         |          | Label for true                              |
| false_label | null or string     | null         |          | Label for false                             |

### Example

``` php
$this->columnBuilder
    ->add('visible', BooleanColumn::class, array(
        'title' => 'Visible',
        'true_icon' => 'glyphicon glyphicon-ok',
        'false_icon' => 'glyphicon glyphicon-remove',
        'true_label' => 'yes',
        'false_label' => 'no',
    ))
;
```
___

## 3. Virtual column

Represents a virtual column.

### Default template

SgDatatablesBundle:Column:column.html.twig

### Options

All options of [Column](#1-column), except `data` and `join_type`.

The options `searchable` and `orderable` are `false`.

**Additional:**

| Option        | Type               | Default | Required | Description     |
|---------------|--------------------|---------|----------|-----------------|
| order_column  | null or string     | null    |          | The name of an existing column that is used for ordering. |
| search_column | null or string     | null    |          | The name of an existing column that is used for searching. |

### Example

``` php
$this->columnBuilder
    ->add('test', VirtualColumn::class, array(
        'title' => 'Test virtual column',
        'searchable' => true,
        'orderable' => true,
        'order_column' => 'title',
        'search_column' => 'title',
    ))
;
```
___

## 4. Action column

A Column to display CRUD action labels or buttons.

### Default template

SgDatatablesBundle:Column:column.html.twig

### Options

| Option          | Type               | Default           | Required | Description |
|-----------------|--------------------|-------------------|----------|-------------|
| cell_type       | null or string     | null              |          | Cell type to be created for a column. |
| class_name      | null or string     | null              |          | Class to assign to each cell in the column. |
| content_padding | null or string     | null              |          | Add padding to the text content used when calculating the optimal with for a table. |
| name            | null or string     | null              |          | Set a descriptive name for a column. |
| title           | null or string     | null              |          | Set the column title. |
| visible         | bool               | true              |          | Enable or disable the display of this column. |
| width           | null or string     | null              |          | Column width assignment. |
| add_if          | null or Closure    | null              |          | Add column only if conditions are TRUE. |
| actions         | array              |                   | X        | Contains all the actions. |
| start_html      | null or string     | null              |          | HTML code before all actions. |
| end_html        | null or string     | null              |          | HTML code after all actions. |

### Action options

| Option          | Type             | Default | Required | Description |
|-----------------|------------------|---------|----------|-------------|
| route            | string          |         | X        | The name of the Action route. |
| route_parameters | null or array   | null    |          | The route parameters. |
| icon             | null or string  | null    |          | An icon for the Action. |
| label            | null or string  | null    |          | A label for the Action. |
| confirm          | bool            | false   |          | Show confirm message if true. |
| confirm_message  | null or string  | null    |          | The confirm message. |
| attributes       | null or array   | null    |          | HTML <a> Tag attributes (except 'href'). |
| render_if        | null or Closure | null    |          | Render an Action only if parameter / conditions are TRUE. |
| start_html       | null or string  | null    |          | HTML code before the <a> Tag. |
| end_html         | null or string  | null    |          | HTML code after the <a> Tag. |

### Example

``` php
$this->columnBuilder
    ->add(null, ActionColumn::class, array(
        'title' => 'Actions',
        'start_html' => '<div class="start">',
        'end_html' => '</div>',
        'actions' => array(
            array(
                'route' => 'post_index',
                'icon' => 'glyphicon glyphicon-ok',
                'label' => 'Index',
                'attributes' => array(
                    'rel' => 'tooltip',
                    'title' => 'Index',
                    'class' => 'btn btn-primary btn-xs',
                    'role' => 'button'
                ),
                'confirm' => true,
                'confirm_message' => 'Really?',
                'start_html' => '<div class="start_index_action">',
                'end_html' => '</div>',
            ),
            array(
                'route' => 'post_show',
                'label' => 'Show',
                'route_parameters' => array(
                    'id' => 'id',
                    //'_format' => 'html',
                    //'_locale' => 'en'
                ),
                'render_if' => function($row) {
                    return $row['id'] === 2;
                },
                'attributes' => array(
                    'rel' => 'tooltip',
                    'title' => 'Show',
                    'class' => 'btn btn-primary btn-xs',
                    'role' => 'button'
                ),
                'start_html' => '<div class="start_show_action">',
                'end_html' => '</div>',
            )
        )
    ))

;
```

___

## 5. Multiselect column

Support for Bulk Actions.

### Default template

SgDatatablesBundle:Column:column.html.twig

### Options

All options of [Action Column](#4-action-column).

**Additional Column options:**

| Option        | Type               | Default | Required | Description     |
|---------------|--------------------|---------|----------|-----------------|
| attributes    | null or array      | null    |          | HTML <input> Tag attributes (except 'type' and 'value'). |
| render_if     | null or Closure    | null    |          | Render a Checkbox only if conditions are TRUE. |
| value         | string             | 'id'    |          | A checkbox value, generated by column name. |

### Example

``` php
$this->options->set(array(
    'order' => array(array(1, 'asc')),
));

$this->columnBuilder

```
___

