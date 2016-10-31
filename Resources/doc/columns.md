# Columns

1. [Column](#1-column)
2. [Boolean Column](#2-boolean-column)
3. [Virtual Column](#3-virtual-column)

## 1. Column

Represents the most basic column, including many-to-one and one-to-one relations.

### Default template

SgDatatablesBundle:column:column.html.twig

### Options

With 'null' initialized options uses the default value of the DataTables plugin.

| Option          | Type               | Default           | Required | Description |
|-----------------|--------------------|-------------------|----------|-------------|
| cell_type       | null or string     | null              |          | Cell type to be created for a column. |
| class_name      | null or string     | null              |          | Class to assign to each cell in the column. |
| content_padding | null or string     | null              |          | Add padding to the text content used when calculating the optimal with for a table. |
| default_content | null or string     | null              |          | Set default, static, content for a column. |
| name            | null or string     | null              |          | Set a descriptive name for a column. |
| orderable       | bool               | true              |          | Enable or disable ordering on this column. |
| order_data      | null, array or int | null              |          | Define multiple column ordering as the default order for a column. |
| order_sequence  | null or array      | null              |          | Order direction application sequence. |
| render_string   | null or string     | null              |          | Render (process) the data for use in the table. |
| render_object   | null or array      | null              |          | |
| render_function | null or string     | null              |          | |
| searchable      | bool               | true              |          | Enable or disable filtering on the data in this column. |
| title           | null or string     | null              |          | Set the column title. |
| visible         | bool               | true              |          | Enable or disable the display of this column. |
| width           | null or string     | null              |          | Column width assignment. |
| add_if          | null or Closure    | null              |          | Add column only if conditions are TRUE. |
| join_type       | string             | 'leftJoin'        |          | Join type (default: 'leftJoin'), if the column represents an association. |
| type_of_field   | null or string     | null (autodetect) |          | Set the data type itself for ordering (example: integer instead string). |
| editable        | bool               | false             |          | Enable edit mode for this column. |
| editable_if     | null or Closure    | null              |          | Enable edit mode for this column only if conditions are TRUE. |


### Example

``` php
$this->columnBuilder
    ->add('title', Column::class, array(
        'title' => 'title',
        'searchable' => true, // default
        'orderable' => true, // default
        'type_of_field' => 'integer' // Example: If numbers are stored as a string, try  this option for a better ordering.
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
___

## 2. Boolean column

Represents a column, optimized for boolean values.

### Default template

SgDatatablesBundle:column:boolean.html.twig

### Options

All options of [Column](#1-column).

**Additional:**

| Option      | Type               | Default | Required | Description     |
|-------------|--------------------|---------|----------|-----------------|
| true_icon   | null or string     | null    |          | Icon for true   |
| false_icon  | null or string     | null    |          | Icon for false  |
| true_label  | null or string     | null    |          | Label for true  |
| false_label | null or string     | null    |          | Label for false |

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

All options of [Column](#1-column).

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
