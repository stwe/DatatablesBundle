# Callbacks

| Callback            | Type          | Default | Description |
|---------------------|---------------|---------|-------------|
| created_row         | array or null | null    | Callback for whenever a TR element is created for the table's body.|
| draw_callback       | array or null | null    | Function that is called every time DataTables performs a draw.|
| footer_callback     | array or null | null    | Footer display callback function.|
| format_number       | array or null | null    | Number formatting callback function.|
| header_callback     | array or null | null    | Header display callback function.|
| info_callback       | array or null | null    | Table summary information display callback.|
| init_complete       | array or null | null    | Initialisation complete callback.|
| pre_draw_callback   | array or null | null    | Pre-draw callback.|
| row_callback        | array or null | null    | Row draw callback.|
| state_load_callback | array or null | null    | Callback that defines where and how a saved state should be loaded.|
| state_loaded        | array or null | null    | State loaded callback.|
| state_load_params   | array or null | null    | State loaded - data manipulation callback.|
| state_save_callback | array or null | null    | Callback that defines how the table state is stored and where.|
| state_save_params   | array or null | null    | State save - data manipulation callback.|

**Example**

``` php
class PostDatatable extends AbstractDatatable
{
    public function buildDatatable(array $options = array())
    {
        // ...

        $this->callbacks->set(array(
            'row_callback' => array(
                'template' => ':post:row_callback.js.twig',
                'vars' => array('id' => '2'),
            ),
            'init_complete' => array(
                'template' => ':post:init.js.twig',
            ),
        ));

        // ...
    }
    
    // ...
}
```

``` js
// row_callback.js.twig

function rowCallback(nRow, aData, index) {
    var id = "{{ id }}";
    var $nRow = $(nRow);

    if (aData.id == id) {
        $nRow.css({"background-color": "red"});
    }

    return nRow;
}
```

``` js
// init.js.twig

function init(settings, json) {
    alert('Init complete.');
}
```
