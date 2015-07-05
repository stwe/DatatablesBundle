# CHANGELOG

## v0.7.1

### Automatically call buildDatatableView() is deprecated. Use buildDatatable() instead.

``` php
class PostDatatable extends AbstractDatatableView
{
    // ...

    public function buildDatatable()
    {
        // ...
    }

    // ...
}

public function indexAction()
{
    $datatable = $this->get('app.datatable.post');
    $datatable->buildDatatable();

    return array(
        'datatable' => $datatable,
    );
}

public function indexResultsAction()
{
    $datatable = $this->get('app.datatable.post');
    $datatable->buildDatatable();

    $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

    return $query->getResponse();
}
```
