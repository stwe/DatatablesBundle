# Columns

## Column

Represents the most basic column, including many-to-one and one-to-one relations.

### Options

- searchable
- sortable
- visible
- title
- render
- class
- default
- width

### Example

``` php
$this->columnBuilder
    ->add('id', 'column', array(
            'title' => 'Id',
            'searchable' => false
        ))
    ->add('username', 'column', array(
            'title' => 'Username',
            'searchable' => false
        ))
    ->add('email', 'column', array(
            'title' => 'Email'
        ))
    ->add('enabled', 'column', array(
            'title' => 'Enabled',
            'searchable' => false,
            'width' => '90',
            'render' => 'render_boolean_icons'
        ));
```

For many-to-one associations (e.g. many events to one calendar):

``` php
$this->columnBuilder
    ->add('calendar.id', 'column', array(
            'title' => 'Calendar'
        ));
```

The tables headers can be translated:

``` php
$this->columnBuilder
    ->add('title', 'column', array(
            'title' => array('label' => 'translated.title', 'translation_domain' => 'your_translation_domain'),
            'searchable' => true
        ))
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

### Example

``` php
$this->columnBuilder
    ->add('edit', 'action', array(
            'route' => 'post_edit',
            'parameters' => array(
                'id' => 'id'
            ),
            'attributes' => array(
                'rel' => 'tooltip',
                'title' => 'Edit User'
            ),
            'icon' => BootstrapDatatableTheme::DEFAULT_EDIT_ICON
        ))
    ->add('show', 'action', array(
            'route' => 'post_show',
            'parameters' => array(
                'id' => 'id'
            ),
            'attributes' => array(
                'rel' => 'tooltip',
                'title' => 'Show User'
            ),
            //'label' => 'Show',
            'label' => array('label' => 'test.show', 'translation_domain' => 'msg')
        ));

```

## Array column

Represents a column for many-to-many or one-to-many associations.

### Options

All options of `column`.

### Example

``` php
$this->columnBuilder
    ->add('posts.title', 'array', array(
            'title' => 'Posts'
        ))
    ->add('comments.title', 'array', array(
            'title' => 'Comments'
        ));
```

## Boolean column

Represents a boolean column.

### Options

All options of `column`.

### Example

``` php
$this->columnBuilder
    ->add('enabled', 'boolean', array(
            'title' => 'Enabled',
            'searchable' => false,
            'width' => '90'
        ));
```

## DateTime column

Represents a datetime column.

### Options

All options of `column`.

### Example

``` php
$this->columnBuilder
    ->add('lastLogin', 'datetime', array(
            'title' => 'Last Login'
        ))
```