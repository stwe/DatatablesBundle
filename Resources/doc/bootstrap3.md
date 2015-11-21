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

## 3. Custom DOM model

You can define your own DOM model, following the syntax given on https://datatables.net/reference/option/dom
To do so, set the 'dom' and 'force_dom' options in your Datatable class.
'force_dom' is needed if you use integration options, to allow you to ovveride bootstrap's dom

```php
class PostDatatable extends AbstractDatatableView
{
    public function buildDatatable($locale = null)
    {
        // ...

        $this->options->set(array(
            // ...
            // default bootstrap dom for datatable. modify it to your needs
            'dom' => "<'row'<'col-sm-6'l><'col-sm-6'f>>" .
                    "<'row'<'col-sm-12'tr>>" .
                    "<'row'<'col-sm-5'i><'col-sm-7'p>>",
            'force_dom' => true,
        ));

        // ...
    }
```