# Columns

## Column

Represents the most basic column, including many-to-one and one-to-one relations.

### Default Template

SgDatatablesBundle:Column:column.html.twig

### Options

| Option     | Type        | Default |
|------------|-------------|---------|
| class      | string      | ""      |
| padding    | string      | ""      |
| name       | string      | ""      |
| orderable  | boolean     | true    |
| render     | null|string | null    |
| searchable | boolean     | true    |
| title      | string      | ""      |
| type       | string      | ""      |
| visible    | boolean     | true    |
| width      | string      | ""      |
| default    | string      | ""      |

### Example

``` php
$this->columnBuilder
    ->add("title", "column", array(
            "title" => "title",
            "searchable" => false,
            "orderable" => false,
            "default" => "default title value"
        ));
```

For many-to-one associations:

``` php
$this->columnBuilder
    ->add("createdBy.username", "column", array(
            "title" => "CreatedBy"
        ))
    ->add("updatedBy.username", "column", array(
            "title" => "UpdatedBy"
        ));
```

## Action column

Represents an action column.

### Default Template

SgDatatablesBundle:Column:action.html.twig

### Options

| Option     | Type        | Default |
|------------|-------------|---------|
| class      | string      | ""      |
| padding    | string      | ""      |
| name       | string      | ""      |
| title      | string      | ""      |
| type       | string      | ""      |
| visible    | boolean     | true    |
| width      | string      | ""      |
| start_html | string      | ""      |
| end_html   | string      | ""      |
| actions    | array       | array() |

### Action options

| Option           | Type        | Default |
|------------------|-------------|---------|
| route            | string      | ""      |
| route_parameters | array       | array() |
| icon             | string      | ""      |
| label            | string      | ""      |
| confirm          | boolean     | false   |
| confirm_message  | string      | ""      |
| attributes       | array       | array() |
| role             | string      | ""      |
| renderif         | array       | array() |

### Example

``` php
$this->columnBuilder
    ->add(null, "action", array(
        "title" => "Actions",
        "start_html" => '<div class="wrapper_example_class">',
        "end_html" => '</div>',
        "actions" => array(
            array(
                "route" => "post_edit",
                "route_parameters" => array(
                    "id" => "id"
                ),
                "icon" => "glyphicon glyphicon-edit",
                "attributes" => array(
                    "rel" => "tooltip",
                    "title" => "Edit",
                    "class" => "btn btn-primary btn-xs",
                    "role" => "button"
                ),
                "confirm" => true,
                "confirm_message" => "Are you sure?",
                "role" => "ROLE_ADMIN",
                "renderif" => array(
                    "enabled"
                )
            ),
            array(
                "route" => "post_show",
                "route_parameters" => array(
                    "id" => "id"
                ),
                "label" => "Show",
                "attributes" => array(
                    "rel" => "tooltip",
                    "title" => "Show",
                    "class" => "btn btn-default btn-xs",
                    "role" => "button"
                ),
                "role" => "ROLE_USER",
                "renderif" => array(
                    "enabled"
                )
            )
        )
    ));
```

## Array column

Represents a column for many-to-many or one-to-many associations.

### Default Template

SgDatatablesBundle:Column:column.html.twig

### Options

| Option     | Type        | Default |
|------------|-------------|---------|
| class      | string      | ""      |
| padding    | string      | ""      |
| name       | string      | ""      |
| orderable  | boolean     | true    |
| render     | null|string | null    |
| searchable | boolean     | true    |
| title      | string      | ""      |
| type       | string      | ""      |
| visible    | boolean     | true    |
| width      | string      | ""      |
| default    | string      | ""      |
| read_as    | string      |         |

### Example

``` php
$this->columnBuilder
    ->add("tags.name", "array", array(
            "title" => "Tags",
            "read_as" => "tags[, ].name"
        ));
```

## Boolean column

Represents a boolean column.

### Default Template

SgDatatablesBundle:Column:boolean.html.twig

### Options

| Option      | Type        | Default           |
|-------------|-------------|-------------------|
| class       | string      | ""                |
| padding     | string      | ""                |
| name        | string      | ""                |
| orderable   | boolean     | true              |
| render      | null|string | render_boolean    |
| searchable  | boolean     | true              |
| title       | string      | ""                |
| type        | string      | ""                |
| visible     | boolean     | true              |
| width       | string      | ""                |
| true_icon   | string      | ""                |
| false_icon  | string      | ""                |
| true_label  | string      | ""                |
| false_label | string      | ""                |

### Example

``` php
$this->columnBuilder
    ->add("visible", "boolean", array(
            "title" => "Visible",
            "true_icon" => "glyphicon glyphicon-ok",
            "false_icon" => "glyphicon glyphicon-remove",
            "true_label" => "yes",
            "false_label" => "no"
        ));
```

## DateTime column

Represents a datetime column.

### Default Template

SgDatatablesBundle:Column:datetime.html.twig

### Options

| Option      | Type        | Default           |
|-------------|-------------|-------------------|
| class       | string      | ""                |
| padding     | string      | ""                |
| name        | string      | ""                |
| orderable   | boolean     | true              |
| render      | null|string | render_datetime   |
| searchable  | boolean     | true              |
| title       | string      | ""                |
| type        | string      | ""                |
| visible     | boolean     | true              |
| width       | string      | ""                |
| format      | string      | "lll"             |

### Example

``` php
$this->columnBuilder
    ->add("createdAt", "datetime", array(
            "title" => "Created",
            "format" => "LLL" // default = "lll"
        ));
```

## Timeago column

Represents a timeago column.

### Default Template

SgDatatablesBundle:Column:timeago.html.twig

### Options

| Option      | Type        | Default           |
|-------------|-------------|-------------------|
| class       | string      | ""                |
| padding     | string      | ""                |
| name        | string      | ""                |
| orderable   | boolean     | true              |
| render      | null|string | render_timeago    |
| searchable  | boolean     | true              |
| title       | string      | ""                |
| type        | string      | ""                |
| visible     | boolean     | true              |
| width       | string      | ""                |

### Example

``` php
$this->columnBuilder
    ->add("createdAt", "timeago", array(
            "title" => "Created"
        ));
```

## Virtual column

Represents a virtual column.

### Default Template

SgDatatablesBundle:Column:column.html.twig

### Options

| Option     | Type        | Default |
|------------|-------------|---------|
| class      | string      | ""      |
| padding    | string      | ""      |
| name       | string      | ""      |
| render     | null|string | null    |
| title      | string      | ""      |
| type       | string      | ""      |
| visible    | boolean     | true    |
| width      | string      | ""      |
| default    | string      | ""      |
| label      | string      | ""      |
| attributes | array       | array() |
| renderif   | array       | array() |

### Example

``` php
$this->columnBuilder
    ->add("a virtual field", "virtual");
```
