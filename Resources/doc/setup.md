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
                    'render_if' => function() {
                        return ($this->authorizationChecker->isGranted('ROLE_ADMIN'));
                    },
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
            'row_callback' => array(
                'template' => ':post:row_callback.js.twig',
                'vars' => array('id' => '2')
            ),
            'init_complete' => array(
                'template' => ':post:init.js.twig'
            )
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

### Options

| Top action | Type    | Default |          |
|------------|---------|---------|----------|
| start_html | string  | ''      |          |
| end_html   | string  | ''      |          |
| add_if     | Closure | null    |          |
| actions    | array   |         | required |

### Action options

| Option           | Type        | Default                      |                       |
|------------------|-------------|------------------------------|-----------------------|
| route            | string      |                              | required              |
| route_parameters | array       | array()                      | Is currently not used |
| icon             | string      | ''                           |                       |
| label            | string      | ''                           |                       |
| confirm          | boolean     | false                        |                       |
| confirm_message  | string      | 'datatables.bulk.confirmMsg' |                       |
| attributes       | array       | array()                      |                       |
| render_if        | Closure     | null                         |                       |

## 3. Callbacks

| Callback            | Type  | Default |
|---------------------|-------|---------|
| created_row         | array | array() |
| draw_callback       | array | array() |
| footer_callback     | array | array() |
| format_number       | array | array() |
| header_callback     | array | array() |
| info_callback       | array | array() |
| init_complete       | array | array() |
| pre_draw_callback   | array | array() |
| row_callback        | array | array() |
| state_load_callback | array | array() |
| state_loaded        | array | array() |
| state_load_params   | array | array() |
| state_save_callback | array | array() |
| state_save_params   | array | array() |

**Example**

```php
// Datatable class

$this->callbacks->set(array(
    'row_callback' => array(
        'template' => ':post:row_callback.js.twig',
        'vars' => array('id' => '2')
    ),
    'init_complete' => array(
        'template' => ':post:init.js.twig'
    )
));
```

```js
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

```js
// init.js.twig

function init(settings, json) {
    alert('Init complete.');
}
```

## 4. Events

| Event             | Type  | Default |
|-------------------|-------|---------|
| column_sizing     | array | array() |
| column_visibility | array | array() |
| destroy           | array | array() |
| draw              | array | array() |
| error             | array | array() |
| init              | array | array() |
| length            | array | array() |
| order             | array | array() |
| page              | array | array() |
| pre_init          | array | array() |
| pre_xhr           | array | array() |
| processing        | array | array() |
| search            | array | array() |
| state_loaded      | array | array() |
| state_load_params | array | array() |
| state_save_params | array | array() |
| xhr               | array | array() |

**Example**

```php
// Datatable class

$this->events->set(array(
    'processing' => array(
        'template' => ':events:processing.js.twig',
        'vars' => array('testStr' => 'processing test')
    ),
    'order' => array(
        'template' => ':events:order.js.twig',
        'vars' => array('testStr' => 'order test')
    )
));
```

```js
// processing.js.twig

function processing(e, settings, processing) {
    var t = '{{ testStr }}';
    console.info(t);
    console.info(processing);
}


// order.js.twig

function order() {
    var order = oTable.order();
    var t = '{{ testStr }}';
    console.info(t);
    console.info('Ordering on column '+order[0][0]+' ('+order[0][1]+')');
}
```

## 5. Features

| Feature         | Type   | Default | need 3rd party plugin                                           |
|-----------------|--------|---------|-----------------------------------------------------------------|
| auto_width      | bool   | true    |                                                                 |
| defer_render    | bool   | false   |                                                                 |
| info            | bool   | true    |                                                                 |
| jquery_ui       | bool   | false   |                                                                 |
| length_change   | bool   | true    |                                                                 |
| ordering        | bool   | true    |                                                                 |
| paging          | bool   | true    |                                                                 |
| processing      | bool   | true    |                                                                 |
| scroll_x        | bool   | false   |                                                                 |
| scroll_y        | string | ''      |                                                                 |
| searching       | bool   | true    |                                                                 |
| state_save      | bool   | false   |                                                                 |
| delay           | int    | 0       |                                                                 |
| extensions      | array  | array() |                                                                 |
| highlight       | bool   | false   | [jQuery Highlight Plugin](https://github.com/bartaz/sandbox.js) |
| highlight_color | string | 'red'   |                                                                 |

**Caution: For the `highlight` feature you must set `individual_filtering_position` option to 'head'.**

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
| individual_filtering_position | string | 'head'                         |
| use_integration_options       | bool   | false                          |
| force_dom                     | bool   | false                          |
| row_id                        | string | ''                             |
| count_all_results             | bool   | true                           |

## 7. Ajax

| Option   | Type   | Default |          |
|------    |--------|---------|----------|
| url      | string |         | required |
| type     | string | 'GET'   |          |
| pipeline | int    | 0       |          |

## 8. Name
Since the datatable class should extend the ``AbstractDatatableView`` and this one implements ``DatatableViewInterface``, a ``getName`` method is required. The returned value **must only include letters, numbers, underscores or dashes** as it will be a seed for the id of the generated container of the datatable.
