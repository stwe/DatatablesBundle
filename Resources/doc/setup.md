# Setup Datatable Class

1. [Example](#1-example)
2. [Top actions](#2-top-actions)
3. [Callbacks](#3-callbacks)
4. [Events](#4-events)
5. [Features](#5-features)
6. [Options](#6-options)
7. [Ajax](#7-ajax)
8. [Name](#8-name)

## 1. Example

``` php
    public function buildDatatable(array $options = array())
    {
        $this->topActions->set(array(
            'start_html' => '<div class="row"><div class="col-sm-3">',
            'end_html' => '<hr></div></div>',
            'actions' => array(
                array(
                    'route' => $this->router->generate('post_new'),
                    'label' => $this->translator->trans('datatables.actions.new'),
                    'icon' => 'glyphicon glyphicon-plus',
                    //'role' => 'ROLE_USER',
                    'attributes' => array(
                        'rel' => 'tooltip',
                        'title' => $this->translator->trans('datatables.actions.new'),
                        'class' => 'btn btn-primary',
                        'role' => 'button'
                    ),
                )
            )
        ));
    
        $this->callbacks->set(array(
            'init_complete' => ':callbacks:init.js.twig'
        ));
        
        $this->events->set(array(
            'processing' => ':events:processing.js.twig',
            'order' => ':events:order.js.twig'
        ));

        $this->features->set(array(
            'processing' => true,
            // ...
        ));

        $this->ajax->set(array(
            'url' => $this->getRouter()->generate('post_results'),
            'type' => 'GET' // default
        ));

        $this->options->set(array(
            'paging_type' => Style::FULL_PAGINATION,
            'class' => Style::BASE_STYLE,
            'individual_filtering' => false,
            'individual_filtering_position' => 'head'
            // ...
        ));

        // ...
    }
```

## 2. Top actions

### Top actions Options

| Top action | Type   | Default |          |
|------------|--------|---------|----------|
| start_html | string | ''      |          |
| end_html   | string | ''      |          |
| actions    | array  |         | required |

### Action options

| Option           | Type        | Default                      |          |
|------------------|-------------|------------------------------|----------|
| route            | string      |                              | required |
| icon             | string      | ''                           |          |
| label            | string      | ''                           |          |
| confirm          | boolean     | false                        |          |
| confirm_message  | string      | 'datatables.bulk.confirmMsg' |          |
| attributes       | array       | array()                      |          |
| role             | string      | ''                           |          |

## 3. Callbacks

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

**Example**

```php
// Datatable class

$this->callbacks->set(array(
    'init_complete' => ':callbacks:init.js.twig'
));
```

```js
// init.js.twig

function initComplete(settings) {
    alert('DataTables has redrawn the table.');
}
```

## 4. Events

| Event             | Type   | Default |
|-------------------|--------|---------|
| column_sizing     | string | ''      |
| column_visibility | string | ''      |
| destroy           | string | ''      |
| draw              | string | ''      |
| error             | string | ''      |
| init              | string | ''      |
| length            | string | ''      |
| order             | string | ''      |
| page              | string | ''      |
| pre_init          | string | ''      |
| pre_xhr           | string | ''      |
| processing        | string | ''      |
| search            | string | ''      |
| state_loaded      | string | ''      |
| state_load_params | string | ''      |
| state_save_params | string | ''      |
| xhr               | string | ''      |

**Example**

```php
// Datatable class

$this->events->set(array(
    'processing' => ':events:processing.js.twig',
    'order' => ':events:order.js.twig'
));
```

```js
// processing.js.twig

function processing(e, settings, processing) {
    console.info(processing);
}


// order.js.twig

function order() {
    var order = oTable.order();
    console.info('Ordering on column '+order[0][0]+' ('+order[0][1]+')');
}
```

## 5. Features

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
| extensions    | array  | array() |

## 6. Options

| Option                        | Type   | Default                        |
|-------------------------------|--------|--------------------------------|
| display_start                 | int    | 0                              |
| defer_loading                 | int    | -1                             |
| dom                           | string | 'lfrtip'                       |
| length_menu                   | array  | array(10, 25, 50, 100)         |
| order_classes                 | bool   | true                           |
| order                         | array  | array(array(0, 'asc'))         |
| order_multi                   | bool   | true                           |
| page_length                   | int    | 10                             |
| paging_type                   | string | Style::FULL_NUMBERS_PAGINATION |
| renderer                      | string | ''                             |
| scroll_collapse               | bool   | false                          |
| search_delay                  | int    | 0                              |
| state_duration                | int    | 7200                           |
| stripe_classes                | array  | array()                        |
| class                         | string | Style::BASE_STYLE              |
| individual_filtering          | bool   | false                          |
| individual_filtering_position | string | 'foot'                         |
| use_integration_options       | bool   | false                          |
| force_dom                     | bool   | false                          |

## 7. Ajax

| Option | Type   | Default |
|------  |--------|---------|
| url    | string | ''      |
| type   | string | 'GET'   |
| data   | string | ''      |

## 8. Name
Since the datatable class should extend the ``AbstractDatatableView`` and this one implements ``DatatableViewInterface``, a ``getName`` method is required. The returned value **must only include letters, numbers, underscores or dashes** as it will be a seed for the id of the generated container of the datatable.
