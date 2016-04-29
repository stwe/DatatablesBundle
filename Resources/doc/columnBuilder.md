# How to use the ColumnBuilder

## Add columns

```php
class PostDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        // ...

        $this->columnBuilder
            ->add('id', 'column', array(
                'title' => 'Id',
            ))
            ->add('title', 'column', array(
                'title' => 'Title',
            ))
        ;
    }
    
    // ...
}
```

## Add and remove columns in your controller

Sometimes is it necessary to add or remove one or more columns. You can do this in your controller.

```php
public function indexAction()
{
    $datatable = $this->get('app.datatable.post');
    $datatable->buildDatatable();
    
    // remove by data 
    $datatable->getColumnBuilder()->removeByData('createdby.id');
    // or remove by key
    $datatable->getColumnBuilder()->removeByKey(2);
    
    // create a new column
    $datatable->getColumnBuilder()
        ->add('title', 'column', array(
            'title' => 'Title',
        ));

    return array(
        'datatable' => $datatable,
    );
}
```
