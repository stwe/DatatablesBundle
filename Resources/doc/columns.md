# Columns

## 1. Column

Represents the most basic column, including many-to-one and one-to-one relations.

### Default template

SgDatatablesBundle:Column:column.html.twig

### Options

| Option               | Type           | Default |
|----------------------|----------------|---------|
| class                | string         | ''      |
| padding              | string         | ''      |
| name                 | string         | ''      |
| orderable            | boolean        | true    |
| render               | null or string | null    |
| searchable           | boolean        | true    |
| title                | string         | ''      |
| type                 | string         | ''      |
| visible              | boolean        | true    |
| width                | string         | ''      |
| search_type          | string         | 'like'  |
| filter_type          | string         | 'text'  |
| filter_options       | array          | array() |
| filter_property      | string         | ''      |
| filter_search_column | string         | ''      |
| default              | string         | ''      |

### Example

``` php
$this->columnBuilder
    ->add('title', 'column', array(
            'title' => 'title',
            'searchable' => false,
            'orderable' => false,
            'default' => 'default title value'
        ));
```

For many-to-one associations:

``` php
$this->columnBuilder
    ->add('createdBy.username', 'column', array(
            'title' => 'CreatedBy'
        ))
    ->add('updatedBy.username', 'column', array(
            'title' => 'UpdatedBy'
        ));
```
___

## 2. Array column

Represents a column for many-to-many or one-to-many associations.

### Default template

SgDatatablesBundle:Column:column.html.twig

### Options

| Option               | Type           | Default |          |
|----------------------|----------------|---------|----------|
| class                | string         | ''      |          |
| padding              | string         | ''      |          |
| name                 | string         | ''      |          |
| orderable            | boolean        | true    |          |
| render               | null or string | null    |          |
| searchable           | boolean        | true    |          |
| title                | string         | ''      |          |
| type                 | string         | ''      |          |
| visible              | boolean        | true    |          |
| width                | string         | ''      |          |
| search_type          | string         | 'like'  |          |
| filter_type          | string         | 'text'  |          |
| filter_options       | array          | array() |          |
| filter_property      | string         | ''      |          |
| filter_search_column | string         | ''      |          |
| default              | string         | ''      |          |
| data                 | string         |         | required |

### Example

``` php
$this->columnBuilder
    ->add('posts.title', 'array', array(
            'title' => 'Posts',
            'data' => 'posts[, ].title' // required option
        ));
        ->add('posts.comments.title', 'array', array(
            'title' => 'Comments titles',
            'render' => 'render_count', // set this render function for a counter
            'data' => 'posts[, ].comments[, ].title'
        ))
```
___

## 3. Virtual column

Represents a virtual column.

### Default template

SgDatatablesBundle:Column:column.html.twig

### Options

see: Column

### Example

``` php
$this->columnBuilder
    ->add('a virtual field', 'virtual', array(
            'title' => 'virtual'
    ));
```
___

## 4. Boolean column

Represents a boolean column.

### Default template

SgDatatablesBundle:Column:boolean.html.twig

### Options

| Option               | Type           | Default                                  |
|----------------------|----------------|------------------------------------------|
| class                | string         | ''                                       |
| padding              | string         | ''                                       |
| name                 | string         | ''                                       |
| orderable            | boolean        | true                                     |
| render               | null or string | render_boolean                           |
| searchable           | boolean        | true                                     |
| title                | string         | ''                                       |
| type                 | string         | ''                                       |
| visible              | boolean        | true                                     |
| width                | string         | ''                                       |
| true_icon            | string         | ''                                       |
| false_icon           | string         | ''                                       |
| true_label           | string         | ''                                       |
| false_label          | string         | ''                                       |
| search_type          | string         | 'like'                                   |
| filter_type          | string         | 'select'                                 |
| filter_options       | array          | ['' => 'Any', '1' => 'Yes', '0' => 'No'] |
| filter_property      | string         | ''                                       |
| filter_search_column | string         | ''                                       |

### Example

``` php
$this->columnBuilder
    ->add('visible', 'boolean', array(
            'title' => 'Visible',
            'true_icon' => 'glyphicon glyphicon-ok',
            'false_icon' => 'glyphicon glyphicon-remove',
            'true_label' => 'yes',
            'false_label' => 'no'
        ));
```
___

## 5. DateTime column

Represents a datetime column.

### Default template

SgDatatablesBundle:Column:datetime.html.twig

### Options

| Option               | Type           | Default         |
|----------------------|----------------|-----------------|
| class                | string         | ''              |
| padding              | string         | ''              |
| name                 | string         | ''              |
| orderable            | boolean        | true            |
| render               | null or string | render_datetime |
| searchable           | boolean        | true            |
| title                | string         | ''              |
| type                 | string         | ''              |
| visible              | boolean        | true            |
| width                | string         | ''              |
| search_type          | string         | 'like'          |
| filter_type          | string         | 'text'          |
| filter_options       | array          | array()         |
| filter_property      | string         | ''              |
| filter_search_column | string         | ''              |
| date_format          | string         | 'lll'           |

### Example

``` php
$this->columnBuilder
    ->add('createdAt', 'datetime', array(
            'title' => 'Created',
            'format' => 'LLL' // default = "lll"
        ));
```
___

## 6. Timeago column

Represents a timeago column.

### Default template

SgDatatablesBundle:Column:timeago.html.twig

### Options

| Option               | Type           | Default        |
|----------------------|----------------|----------------|
| class                | string         | ''             |
| padding              | string         | ''             |
| name                 | string         | ''             |
| orderable            | boolean        | true           |
| render               | null or string | render_timeago |
| searchable           | boolean        | true           |
| title                | string         | ''             |
| type                 | string         | ''             |
| visible              | boolean        | true           |
| width                | string         | ''             |
| search_type          | string         | 'like'         |
| filter_type          | string         | 'text'         |
| filter_options       | array          | array()        |
| filter_property      | string         | ''             |
| filter_search_column | string         | ''             |

### Example

``` php
$this->columnBuilder
    ->add('createdAt', 'timeago', array(
            'title' => 'Created'
        ));
```
___

## 7. Action column

Represents an action column.

### Default template

SgDatatablesBundle:Column:action.html.twig

### Options

| Option     | Type        | Default |          |
|------------|-------------|---------|----------|
| class      | string      | ''      |          |
| padding    | string      | ''      |          |
| name       | string      | ''      |          |
| title      | string      | ''      |          |
| type       | string      | ''      |          |
| visible    | boolean     | true    |          |
| width      | string      | ''      |          |
| start_html | string      | ''      |          |
| end_html   | string      | ''      |          |
| actions    | array       |         | required |

### Action options

| Option           | Type        | Default |          |
|------------------|-------------|---------|----------|
| route            | string      |         | required |
| route_parameters | array       | array() |          |
| icon             | string      | ''      |          |
| label            | string      | ''      |          |
| confirm          | boolean     | false   |          |
| confirm_message  | string      | ''      |          |
| attributes       | array       | array() |          |
| role             | string      | ''      |          |
| render_if        | array       | array() |          |

### Example

``` php
$this->columnBuilder
    ->add(null, 'action', array(
        'title' => 'Actions',
        'start_html' => '<div class="wrapper_example_class">',
        'end_html' => '</div>',
        'actions' => array( // required option
            array(
                'route' => 'post_edit',
                'route_parameters' => array(
                    'id' => 'id'
                ),
                'icon' => 'glyphicon glyphicon-edit',
                'attributes' => array(
                    'rel' => 'tooltip',
                    'title' => 'Edit',
                    'class' => 'btn btn-primary btn-xs',
                    'role' => 'button'
                ),
                'confirm' => true,
                'confirm_message' => 'Are you sure?',
                'role' => 'ROLE_ADMIN',
                'render_if' => array(
                    'enabled'
                )
            ),
            array(
                'route' => 'post_show',
                'route_parameters' => array(
                    'id' => 'id'
                ),
                'label' => 'Show',
                'attributes' => array(
                    'rel' => 'tooltip',
                    'title' => 'Show',
                    'class' => 'btn btn-default btn-xs',
                    'role' => 'button'
                ),
                'role' => 'ROLE_USER',
                'render_if' => array(
                    'enabled'
                )
            )
        )
    ));
```
___

## 8. Multiselect column

### Default template

SgDatatablesBundle:Column:multiselect.html.twig

### Options

| Option     | Type        | Default |          |
|------------|-------------|---------|----------|
| class      | string      | ''      |          |
| padding    | string      | ''      |          |
| name       | string      | ''      |          |
| title      | string      | ''      |          |
| type       | string      | ''      |          |
| visible    | boolean     | true    |          |
| width      | string      | ''      |          |
| start_html | string      | ''      |          |
| end_html   | string      | ''      |          |
| actions    | array       |         | required |
| attributes | array       | array() |          |
| value      | string      | 'id'    |          |

### Multiselect-Action options

| Option           | Type        | Default |          |
|------------------|-------------|---------|----------|
| icon             | string      | ''      |          |
| route            | string      |         | required |
| label            | string      | ''      |          |
| role             | string      | ''      |          |
| route_parameters | array       | array() |          |
| attributes       | array       | array() |          |

### Example

``` php
$this->getColumnBuilder()
    ->add(null, 'multiselect', array(
        'start_html' => '<div class="wrapper" id="testwrapper">',
        'end_html' => '</div>',
        'attributes' => array(
            'class' => 'testclass',
            'name' => 'testname',
        ),
        'actions' => array(
            array(
                'route' => 'post_bulk_delete',
                'label' => 'Delete',
                'role' => 'ROLE_ADMIN',
                'icon' => 'fa fa-times',
                'attributes' => array(
                    'rel' => 'tooltip',
                    'title' => 'Delete',
                    'class' => 'btn btn-primary btn-xs',
                    'role' => 'button'
                ),
            ),
            array(
                'route' => 'post_bulk_disable',
                'label' => 'Disable',
                'icon' => 'fa fa-lock'
            )
        )
    ));
```
