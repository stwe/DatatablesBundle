# Example

``` php
    public function buildDatatableView()
    {
        $this->callbacks->setCallbacks(array(
            'draw_callback' => "function( settings ) {
                                    alert( 'DataTables has redrawn the table' );
                                }"
        ));

        $this->features->setFeatures(array(
            'processing' => true,
            // ...
        ));

        $this->ajax->setOptions(array(
            'url' => $this->getRouter()->generate('post_results'),
            'type' => 'GET' // default
        ));

        $this->options->setOptions(array(
            'paging_type' => Style::FULL_PAGINATION,
            'responsive' => true,
            'class' => Style::BASE_STYLE,
            'individual_filtering' => false,
            'individual_filtering_position' => 'head'
            // ...
        ));

        // ...
    }
```

# Callbacks

| Callback            | Type   | Default |
|---------------------|--------|---------|
| created_row         | string | ''      |
| draw_callback       | string | ''      |
| footer_callback     | string | ''      |
| format_number       | string | ''      |
| header_callback     | string | ''      |
| info_callback       | string | ''      |
| init_complete       | string | ''      |
| pre_draw_callback   | string | ''      |
| row_callback        | string | ''      |
| state_load_callback | string | ''      |
| state_loaded        | string | ''      |
| state_load_params   | string | ''      |
| state_save_callback | string | ''      |
| state_save_params   | string | ''      |

# Features

| Feature       | Type   | Default |
|---------------|--------|---------|
| auto_width    | bool   | true    |
| defer_render  | bool   | false   |
| info          | bool   | true    |
| jquery_ui     | bool   | false   |
| length_change | bool   | true    |
| ordering      | bool   | true    |
| paging        | bool   | true    |
| processing    | bool   | true    |
| scroll_x      | bool   | false   |
| scroll_y      | string | ''      |
| searching     | bool   | true    |
| server_side   | bool   | true    |
| state_save    | bool   | false   |
| delay         | int    | 0       |

# Options

| Option                        | Type   | Default                                    |
|-------------------------------|--------|--------------------------------------------|
| display_start                 | int    | 0                                          |
| defer_loading                 | int    | -1                                         |
| dom                           | string | 'lfrtip'                                   |
| length_menu                   | array  | array(10, 25, 50, 100)                     |
| order_classes                 | bool   | true                                       |
| order                         | array  | array('column' => 0, 'direction' => 'asc') |
| order_multi                   | bool   | true                                       |
| page_length                   | int    | 10                                         |
| paging_type                   | string | Style::FULL_NUMBERS_PAGINATION             |
| renderer                      | string | ''                                         |
| scroll_collapse               | bool   | false                                      |
| search_delay                  | int    | 0                                          |
| state_duration                | int    | 7200                                       |
| stripe_classes                | array  | array()                                    |
| responsive                    | bool   | false                                      |
| class                         | string | Style::BASE_STYLE                          |
| individual_filtering          | bool   | false                                      |
| individual_filtering_position | string | 'foot'                                     |
| use_integration_options       | bool   | false                                      |

# Ajax options

| Option | Type   | Default |
|------  |--------|---------|
| url    | string | ''      |
| type   | string | 'GET'   |
