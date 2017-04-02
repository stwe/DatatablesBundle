# Filters

1. [Enable or disable the search abilities](#1-enable-or-disable-the-search-abilities)
2. [Text Filter](#2-text-filter)
3. [Number Filter](#3-number-filter)
4. [Select Filter](#4-select-filter)
5. [Select2 Filter](#5-select2-filter)
6. [DateRange Filter](#6-daterange-filter)

## 1. Enable or disable the search abilities

``` php
class PostDatatable extends AbstractDatatable
{
    $this->options->set(array(
        'individual_filtering' => true,
        'individual_filtering_position' => 'head', // or 'foot', 'both'
        'order' => array(array(0, 'asc')),
        'order_cells_top' => true,
        //'global_search_type' => 'gt',
        'search_in_non_visible_columns' => true,
    ));
}
```
__

## 2. Text Filter

### Default template

SgDatatablesBundle:filter:input.html.twig

### Options

| Option           | Type           | Default                          | Description    |
|------------------|----------------|----------------------------------|----------------|
| search_type      | string         | 'like'                           | The search type (e.g. 'like'). |
| search_column    | string or null | null                             | Column name, on which the filter is applied, based on options for this column. |
| initial_search   | string or null | null                             | Define an initial search (same as DataTables 'searchCols' option). |
| classes          | string or null | null                             | Additional classes for the html filter element. |
| cancel_button    | bool           | false                            | Renders a Cancel-Button to reset the filter. |
| placeholder      | bool           | true                             | Specifies whether a placeholder is displayed. |
| placeholder_text | string or null | null (The Column Title is used.) | The placeholder text. |

### Example

``` php
$this->columnBuilder
    ->add('test', VirtualColumn::class, array(
        'title' => 'Test virtual',
        'searchable' => true,
        'orderable' => true,
        'order_column' => 'createdBy.username', // use the 'createdBy.username' column for ordering
        'search_column' => 'createdBy.username', // use the 'createdBy.username' column for searching
        'filter' => array(TextFilter::class, array(
            'cancel_button' => true
        ))
    ))
;
```
__

## 3. Number Filter

### Default template

SgDatatablesBundle:filter:input.html.twig

### Options

| Option           | Type           | Default                                        | Description    |
|------------------|----------------|------------------------------------------------|----------------|
| search_type      | string         | 'like'                                         | The search type (e.g. 'like'). |
| search_column    | string or null | null                                           | Column name, on which the filter is applied, based on options for this column. |
| initial_search   | string or null | null                                           | Define an initial search (same as DataTables 'searchCols' option). |
| classes          | string or null | null                                           | Additional classes for the html filter element. |
| cancel_button    | bool           | false                                          | Renders a Cancel-Button to reset the filter. |
| min              | string         | '0'                                            | Minimum value. |
| max              | string         | '100'                                          | Maximum value. |
| step             | string         | '1'                                            | The Step scale factor of the slider. |
| show_label       | bool           | false                                          | Determines whether a label with the current value is displayed. |
| datalist         | array or null  | null                                           | Pre-defined values. |
| type             | string         | 'number' (allowed values: 'number' and 'range) | The <input> type. |

### Example

``` php
$this->columnBuilder
    ->add('id', Column::class, array(
        'title' => 'Id',
        'searchable' => true,
        'orderable' => true,
        'filter' => array(NumberFilter::class, array(
            'classes' => 'test1 test2',
            'search_type' => 'eq',
            'cancel_button' => true,
            'type' => 'number',
            'min' => '1',
            'max' => '120',
            'show_label' => true,
            //'datalist' => array('3', '50', '75')
        )),
    ))
;
```
__

## 4. Select Filter

### Default template

SgDatatablesBundle:filter:select.html.twig

### Options

| Option              | Type           | Default | Description    |
|---------------------|----------------|---------|----------------|
| search_type         | string         | 'like'  | The search type (e.g. 'like'). |
| search_column       | string or null | null    | Column name, on which the filter is applied, based on options for this column. |
| initial_search      | string or null | null    | Define an initial search (same as DataTables 'searchCols' option). |
| classes             | string or null | null    | Additional classes for the html filter element. |
| cancel_button       | bool           | false   | Renders a Cancel-Button to reset the filter. |
| select_search_types | array          | array() | This allows to define a search type (e.g. 'like' or 'isNull') for each item in 'selectOptions'. |
| select_options      | array          | array() | Select options for this filter type (e.g. for boolean column: '1' => 'Yes', '0' => 'No'). |
| multiple            | bool           | false   | Lets the user select more than one option in the select list. |

### Example

``` php
$this->columnBuilder
    ->add('visible', BooleanColumn::class, array(
        'title' => 'Visible',
        'filter' => array(SelectFilter::class, array(
            'classes' => 'test1 test2',
            'search_type' => 'eq',
            'multiple' => true,
            'select_options' => array(
                '' => 'Any',
                '1' => 'Yes',
                '0' => 'No'
            ),
            'cancel_button' => true,
        )),
    ))
    ->add('title', Column::class, array(
        'title' => 'Title',
        'searchable' => true,
        'orderable' => true,
        'filter' => array(SelectFilter::class, array(
            'multiple' => true,
            'cancel_button' => true,
            'select_search_types' => array(
                '' => null,
                '2' => 'like',
                '1' => 'eq',
                'send_isNull' => 'isNull',
                'send_isNotNull' => 'isNotNull'
            ),
            'select_options' => array(
                '' => 'Any',
                '2' => 'Title with the digit 2',
                '1' => 'Title with the digit 1',
                'send_isNull' => 'is Null',
                'send_isNotNull' => 'is not Null'
            ),
        )),
        'type_of_field' => 'integer', // If the title consists only of digits.
    ))
    ->add('createdBy.username', Column::class, array(
        'title' => 'Created by',
        'filter' => array(SelectFilter::class, array(
            'select_options' => array('' => 'All') + $this->getOptionsArrayFromEntities($users, 'username', 'username'),
            'search_type' => 'eq',
            'cancel_button' => true
        ))
    ))
;
```
__

## 5. Select2 Filter

**Be sure to install the [Select2](https://select2.github.io/) plugin before using the Filter.**

### Default template

SgDatatablesBundle:filter:select2.html.twig

### Options

All options of [Select Filter](#4-select-filter).

**Additional:**

| Option      | Type           | Default           | Description    |
|-------------|----------------|-------------------|----------------|
| placeholder | string or null | null              | Displaying a placeholder. |
| allow_clear | bool or null   | null              | Will reset the selection. |
| tags        | bool or null   | null              | Tagging support. |
| language    | string or null | null (get locale) | i18n language code. |
| url         | string or null | null              | URL to get the results from. |
| delay       | integer        | 250               | Wait some milliseconds before triggering the request. |
| cache       | bool           | true              | The AJAX cache. |

### Example

``` php
$this->columnBuilder
    ->add('createdBy.username', Column::class, array(
        'title' => 'Created by',
        'searchable' => true,
        'orderable' => true,
        'filter' => array(Select2Filter::class, array(
            'select_options' => array('' => 'All') + $this->getOptionsArrayFromEntities($users, 'username', 'username'),
            'search_type' => 'eq',
            'cancel_button' => true,
        )),
;
```

**Remote example:**

``` php
$this->columnBuilder
    ->add('createdBy.username', Column::class, array(
        'title' => 'Created by',
        'searchable' => true,
        'orderable' => true,
        'width' => '100%', // the input field is otherwise too narrow
        'filter' => array(Select2Filter::class, array(
            //'select_options' => array('' => 'All') + $this->getOptionsArrayFromEntities($users, 'username', 'username'),
            'search_type' => 'eq',
            'cancel_button' => true,
            'url' => 'select2_createdby_usernames',
        )),
    ))
;
```

``` php
/**
 * @param Request $request
 *
 * @Route("/select2-usernames", name="select2_createdby_usernames")
 *
 * @return JsonResponse|Response
 */
public function select2CreatedByUsersnames(Request $request)
{
    if ($request->isXmlHttpRequest()) {
        $em = $this->getDoctrine()->getManager();
        $users = $em->getRepository('AppBundle:User')->findAll();

        $result = array();

        foreach ($users as $user) {
            $result[$user->getId()] = $user->getUsername();
        }

        return new JsonResponse($result);
    }

    return new Response('Bad request.', 400);
}
```
__

## 6. DateRange Filter

**Be sure to install the [Moment.js](https://momentjs.com/) and the [Bootstrap Date Range Picker](http://www.daterangepicker.com/) plugins before using the Filter.**

### Default template

SgDatatablesBundle:filter:daterange.html.twig

### Options

| Option           | Type           | Default                          | Description    |
|------------------|----------------|----------------------------------|----------------|
| search_column    | string or null | null                             | Column name, on which the filter is applied, based on options for this column. |
| initial_search   | string or null | null                             | Define an initial search (same as DataTables 'searchCols' option). |
| classes          | string or null | null                             | Additional classes for the html filter element. |
| cancel_button    | bool           | false                            | Renders a Cancel-Button to reset the filter. |
| placeholder      | bool           | true                             | Specifies whether a placeholder is displayed. |
| placeholder_text | string or null | null (The Column Title is used.) | The placeholder text. |

### Example

``` php
$this->columnBuilder
    ->add('publishedAt', DateTimeColumn::class, array(
        'title' => 'Published at',
        'date_format' => 'L',
        'filter' => array(DateRangeFilter::class, array(
            'cancel_button' => true,
        )),
        //'timeago' => true
    ))
;
```
__

