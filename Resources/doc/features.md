# Features

With 'null' initialized features uses the default value of the DataTables plugin.

| Feature       | Type           | Default   | Description |
|---------------|----------------|-----------|-------------|
| auto_width    | null or bool   | null      | Feature control DataTables' smart column width handling. |
| defer_render  | null or bool   | null      | Feature control deferred rendering for additional speed of initialisation. |
| info          | null or bool   | null      | Feature control table information display field. |
| length_change | null or bool   | null      | Feature control the end user's ability to change the paging display length of the table. |
| ordering      | null or bool   | null      | Feature control ordering (sorting) abilities in DataTables. |
| paging        | null or bool   | null      | Enable or disable table pagination. |
| processing    | null or bool   | null      | Feature control the processing indicator. |
| scroll_x      | null or bool   | null      | Horizontal scrolling. |
| scroll_y      | null or string | null      | Vertical scrolling. |
| searching     | null or bool   | null      | Feature control search (filtering) abilities. |
| state_save    | null or bool   | null      | State saving - restore table state on page reload. |

``` php
class PostDatatable extends AbstractDatatable
{
    public function buildDatatable(array $options = array())
    {
        // ...

        $this->features->set(array(
            'info' => false,
            'paging' => false,
            'searching' => false,
        ));

        // ...
    }
    
    // ...
}
```
