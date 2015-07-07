# CHANGELOG

## v0.7.1

### Automatically call buildDataTableView() is deprecated. Use buildDataTable() instead.

``` php
class PostDataTable extends AbstractDataTableView
{
    // ...

    public function buildDataTable()
    {
        // ...
    }

    // ...
}

public function indexAction()
{
    $datatable = $this->get('app.datatable.post');
    $datatable->buildDataTable();

    return array(
        'datatable' => $datatable,
    );
}

public function indexResultsAction()
{
    $datatable = $this->get('app.datatable.post');
    $datatable->buildDataTable();

    $query = $this->get('sg_datatables.query')->getQueryFrom($datatable);

    return $query->getResponse();
}
```
