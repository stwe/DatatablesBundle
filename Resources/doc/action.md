# Action

Represents an action column for crud hrefs.

## Options

all options of `column` and additionally:

- route
- parameters
- icon
- label
- attributes

## Example

``` php
$this->columnBuilder
    ->add('edit', 'action', array(
            'route' => 'sg_user_edit',
            'parameters' => array(
                'id' => 'id'
            ),
            'attributes' => array(
                'rel' => 'tooltip',
                'title' => 'Edit User'
            ),
            'icon' => ActionColumn::DEFAULT_EDIT_ICON
        ))
    ->add('show', 'action', array(
            'route' => 'sg_user_show',
            'parameters' => array(
                'id' => 'id'
            ),
            'attributes' => array(
                'rel' => 'tooltip',
                'title' => 'Show User'
            ),
            'icon' => ActionColumn::DEFAULT_SHOW_ICON
        ));
```
