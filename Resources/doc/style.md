# Styling

There are a number of integration packages which can be used to fit DataTables into a site 
which uses some of the popular CSS libraries such as Twitter Bootstrap and Foundation.

The fastest way to get all needed files is to use the [download builder](https://www.datatables.net/download/) of the DataTables vendor. 

The styling can then be set with the `classes` option. For Bootstrap3 this looks as follows:

``` php
class PostDatatable extends AbstractDatatable
{
    public function buildDatatable(array $options = array())
    {
        // ...

        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_3_STYLE, // or Style::BOOTSTRAP_3_STYLE.' table-condensed'
            'paging_type' => Style::FULL_NUMBERS_PAGINATION,
            // ...
            //'dom' => 'rtip'
        ));

        // ...
    }
    
    // ...
}
```
