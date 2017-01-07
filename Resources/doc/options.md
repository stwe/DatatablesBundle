# Options

With 'null' initialized options uses the default value of the DataTables plugin.

| Option                        | Type               | Default   | Description |
|-------------------------------|--------------------|-----------|-------------|
| defer_loading                 | null, int or array | null      | Delay the loading of server-side data until second draw. |
| display_start                 | null or int        | null      | Initial paging start point. |
| dom                           | null or string     | null      | Define the table control elements to appear on the page and in what order. |
| length_menu                   | null or array      | null      | Change the options in the page length select list. |
| order                         | null or array      | null      | Initial order (sort) to apply to the table. |
| order_cells_top               | null or bool       | null      | Control which cell the order event handler will be applied to in a column. |
| order_classes                 | null or bool       | null      | Highlight the columns being ordered in the table's body. |
| order_fixed                   | null or array      | null      | Ordering to always be applied to the table. |
| order_multi                   | null or bool       | null      | Multiple column ordering ability control. |
| page_length                   | null or int        | null      | Change the initial page length (number of rows per page). |
| paging_type                   | null or string     | null      | Pagination button display options. |
| renderer                      | null or string     | null      | Display component renderer types. |
| retrieve                      | null or bool       | null      | Retrieve an existing DataTables instance. |
| row_id                        | null or string     | null      | Id attribute on each tr element in a DataTable. |
| scroll_collapse               | null or bool       | null      | Allow the table to reduce in height when a limited number of rows are shown. |
| search_delay                  | null or int        | null      | Set a throttle frequency for searching. |
| state_duration                | null or int        | null      | Saved state validity duration. |
| stripe_classes                | null or array      | null      | Set the zebra stripe class names for the rows in the table. |
| classes                       | string             | 'display' | To define the style for the table. |
| individual_filtering          | bool               | false     | Enable or disable individual filtering. |
| individual_filtering_position | string             | 'head'    | Position of individual search filter ('head', 'foot' or 'both'). |
| search_in_non_visible_columns | bool               | false     | Determines whether to search in non-visible columns. |
| global_search_type            | string             | 'like'    | The global search type (example: 'eq'). |

``` php
class PostDatatable extends AbstractDatatable
{
    public function buildDatatable(array $options = array())
    {
        // ...

        $this->options->set(array(
            'classes' => Style::BOOTSTRAP_3_STYLE,
            'stripe_classes' => [ 'strip1', 'strip2', 'strip3' ],
            'individual_filtering' => false,
            'individual_filtering_position' => 'both',
            'order' => array(array(1, 'asc')),
            'order_cells_top' => true,
            //'global_search_type' => 'gt',
            'search_in_non_visible_columns' => true,
        ));
        
        // ...
    }
    
    // ...
}
```
