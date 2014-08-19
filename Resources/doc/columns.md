# Columns

## Column

Represents the most basic column, including many-to-one and one-to-one relations.

### Options

- class (string)
- padding (string)
- default (string)
- name (string)
- orderable (boolean)
- render (string)
- searchable (boolean)
- title (string)
- type (string)
- visible (boolean)
- width (string)

### Example

``` php
$this->columnBuilder
    ->add("id", "column", array(
            "title" => "Id",
            "searchable" => false
        ))
    ->add("title", "column", array(
            "searchable" => true,     // default
            "orderable" => true,      // default
            "visible" => true,        // default
//          "title" => "Title",       // default = ""
            "title" => $this->getTranslator()->trans("test.title", array(), "msg"),
            "render" => null,         // default
            "class" => "text-center", // default = ""
            "default" => "",          // default
            "width" => ""             // default
            "type" => ""              // default
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

### Options

all options of `column` and additionally:

- start (string)
- end (string)
- actions (array) with following options:
    * route (string)
    * route_parameters (array)
    * icon (string)
    * label (string)
    * confirm (boolean)
    * confirm_message (string)
    * attributes (array)
    * role (string)
    * renderif (array)

### Example

``` php
$this->columnBuilder
    ->add(null, "action", array(
        "title" => "Actions",
        "start" => '<div class="wrapper_example_class">',
        "end" => '</div>',
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

### Options

All options of `column` and additionally:

- read_as (string)

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

### Options

All options of `column` and additionally:

- true_icon (string)
- false_icon (string)
- true_label (string)
- false_label (string)

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

### Options

All options of `column` and additionally:

- format (string)

### Example

``` php
$this->columnBuilder
    ->add("createdAt", "datetime", array(
            "title" => "Created",
            "format" => "LLL"         // default = "lll"
        ));
```

## Timeago column

Represents a timeago column.

### Options

All options of `column`.

### Example

``` php
$this->columnBuilder
    ->add("createdAt", "timeago", array(
            "title" => "Created"
        ));
```