# Column

Represents the most basic column, including many-to-one relations.

## Options

- searchable
- sortable
- visible
- title
- render
- class
- default
- width

## Example

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