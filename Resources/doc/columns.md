# Columns

## Column

Represents the most basic column, including many-to-one and one-to-one relations.

### Options

- class
- padding
- default
- name
- orderable
- render
- searchable
- title
- type
- visible
- width

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

- route
- parameters
- icon
- label
- attributes
- renderif

### Example

``` php
$this->columnBuilder
    ->add(null, "action", array(
            "route" => "post_edit",
            "parameters" => array(
                "id" => "id"
            ),
            "renderif" => array(
                "visible" // if this attribute is not NULL/FALSE
            ),
            "icon" => BootstrapDatatableTheme::DEFAULT_EDIT_ICON,
            "attributes" => array(
                "rel" => "tooltip",
                "title" => "Edit User",
                "class" => "btn btn-danger btn-xs"
            ),
        ))
    ->add(null, "action", array(
            "route" => "post_show",
            "parameters" => array(
                "id" => "id"
            ),
//          "label" => "Show",
            "label" => $this->getTranslator()->trans("test.show", array(), "msg"),
            "attributes" => array(
                "rel" => "tooltip",
                "title" => "Show User",
                "class" => "btn btn-primary btn-xs"
            )
        ));
```

## Multiaction column

Represents an action column.

### Options

Same as for Action column only wrapped to array under `actions` key + renderif for whole column

### Example

``` php
$this->columnBuilder
    ->add(null, "multiaction", array(
		"actions" => array(
			array(
            	"route" => "post_edit",
            	"parameters" => array(
                	"id" => "id"
            	),
            	"renderif" => array(
                	"visible" // if this attribute is not NULL/FALSE
            	),
            	"icon" => BootstrapDatatableTheme::DEFAULT_EDIT_ICON,
            	"attributes" => array(
               	"rel" => "tooltip",
                	"title" => "Edit User",
                	"class" => "btn btn-danger btn-xs"
            	),
        	), 
			array(
            	"route" => "post_show",
            	"parameters" => array(
                	"id" => "id"
            	),
//          	"label" => "Show",
            	"label" => $this->getTranslator()->trans("test.show", array(), "msg"),
            	"attributes" => array(
                	"rel" => "tooltip",
                	"title" => "Show User",
                	"class" => "btn btn-primary btn-xs"
            	)
        	)
		)
	));
```


## Array column

Represents a column for many-to-many or one-to-many associations.

### Options

All options of `column` and additionally:

- read_as

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

- true_icon
- false_icon
- true_label
- false_label

### Example

``` php
$this->columnBuilder
    ->add("visible", "boolean", array(
            "title" => "Visible",
            "true_icon" => BootstrapDatatableTheme::DEFAULT_TRUE_ICON,
            "false_icon" => BootstrapDatatableTheme::DEFAULT_FALSE_ICON,
            "true_label" => "yes",
            "false_label" => "no"
        ));
```

## DateTime column

Represents a datetime column.

### Options

All options of `column` and additionally:

- format

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