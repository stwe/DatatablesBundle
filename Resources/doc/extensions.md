# Extensions

## Example for the Button and Responsive Extension

### 1. Datatable class

```php
/**
 * Class PostDatatable
 */
class PostDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        // ...

        $this->features->set(array(
            // ...

            'extensions' => array(
                'buttons' =>
                    array(
                        'excel',
                        'pdf',
                        array(
                            'text' => 'Reload',
                            'action' => ':post:reload.js.twig'
                        )
                    ),
                'responsive' => true
            )
        ));
        
        // ...
    }
}
```

### 2. The action template for the Reload-Button

```js
function ( e, dt, node, config ) {
    dt.ajax.reload();
}
```
