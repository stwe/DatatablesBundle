# Events

| Event             | Type          | Default | Description |
|-------------------|---------------|---------|-------------|
| column_sizing     | array or null | null    | Fired when the column widths are recalculated. |
| column_visibility | array or null | null    | Fired when the visibility of a column changes. |
| destroy           | array or null | null    | Fired when a table is destroyed. |
| error             | array or null | null    | An error has occurred during DataTables processing of data. |
| length            | array or null | null    | Fired when the page length is changed. |
| order             | array or null | null    | Fired when the data contained in the table is ordered. |
| page              | array or null | null    | Fired when the table's paging is updated. |
| pre_init          | array or null | null    | Triggered immediately before data load. |
| pre_xhr           | array or null | null    | Fired before an Ajax request is made. |
| processing        | array or null | null    | Fired when DataTables is processing data. |
| search            | array or null | null    | Fired when the table is filtered. |
| state_loaded      | array or null | null    | Fired once state has been loaded and applied. |
| state_load_params | array or null | null    | Fired when loading state from storage. |
| state_save_params | array or null | null    | Fired when saving table state information. |
| xhr               | array or null | null    | Fired when an Ajax request is completed. |

**Example:**

``` php
class PostDatatable extends AbstractDatatable
{
    public function buildDatatable(array $options = array())
    {
        // ...

        $this->events->set(array(
            'xhr' => array(
                'template' => ':post:event.js.twig',
                'vars' => array('table_name' => $this->getName()),
            ),
        ));

        // ...
    }
    
    // ...
}
```

``` js
// event.js.twig

function xhrEvent(e, settings, json, xhr) {
    alert('{{ table_name }}' + ' ajax request is completed.');
}
```
