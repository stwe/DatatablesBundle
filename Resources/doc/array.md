# Array

Represents a column for many-to-many or one-to-many associations.

## Options

All options of `column`.

## Example

``` php
$this->columnBuilder
    ->add('posts.title', 'array', array(
            'title' => 'Posts'
        ))
    ->add('comments.title', 'array', array(
            'title' => 'Comments'
        ));
```