# Integrate Bootstrap3

## 1. Assets (CSS, JavaScript and image files)

Ensure that all required integration files are included. 
The fastest way to get all needed files is to use the [download builder](https://www.datatables.net/download/) of the Datatables vendor. 

## 2. Datatable class

```php
class PostDatatable extends AbstractDatatableView
{
    public function buildDatatable($locale = null)
    {
        // ...

        $this->options->set(array(
            // ...
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            'class' => Style::BOOTSTRAP_3_STYLE, // or Style::BOOTSTRAP_3_STYLE . ' table-condensed'
            'use_integration_options' => true
        ));

        $this->columnBuilder
            ->add('id', 'column', array(
                'title' => 'Id',
            ))
            ->add('title', 'column', array(
                'title' => 'Title',
            ))
            
            // ...
        ;
    }
```
