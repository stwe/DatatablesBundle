# Filtering

1. [Enable or disable the search abilities](#1-enable-or-disable-the-search-abilities)
2. [Server-side individual column filtering](#2-serverside-individual-column-filtering)
3. [Hide the global search box](#3-hide-the-global-search-box)

## 1. Enable or disable the search abilities

```php
class PostDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        // ...

        $this->features->set(array(
            'searching' => true, // true is the default value
        ));
    }
    
    // ...
}
```
__

## 2. ServerSide individual column filtering

[Example](#example)
[Text Filter](#text-filter)
[Select Filter](#select-filter)
[DateRange Filter](#daterange-filter)
[Slider Filter](#slider-filter)

### Example

```php
class PostDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        // ...

        $this->options->set(array(
            'individual_filtering' => true, // default is false
            'individual_filtering_position' => 'head', // or 'both', default is 'foot'
        ));
        
        $users = $this->em->getRepository('AppBundle:User')->findAll();

        $this->columnBuilder
            ->add('title', 'column', array(
                'title' => 'Title',
                'filter' => array('text', array(
                    'search_type' => 'eq',
                    'class' => 'test1 test2'
                ))
            ))
            ->add('visible', 'boolean', array(
                'title' => 'Visible',
                'filter' => array('select', array(
                    'search_type' => 'eq',
                    'select_options' => array('' => 'Any', '1' => 'Yes', '0' => 'No'),
                    'class' => 'test1 test2'
                )),
            ))
            ->add('createdby.username', 'column', array(
                'title' => 'Createdby',
                'filter' => array('select', array(
                    'search_type' => 'eq',
                    'select_options' => array('' => 'All') + $this->getCollectionAsOptionsArray($users, 'username', 'username'),
                ))
            ))
            ->add('publishedAt', 'datetime', array(
                'title' => 'PublishedAt',
                'filter' => array('daterange', array(
                    'class' => 'test1 test2'
                ))
            ))
            ->add('heat.heat', 'progress_bar', array(
                'title' => 'Heat',
                'filter' => array('slider', array(
                    'min' => 1.0,
                    'max' => 12.0,
                    'property' => 'heat.id',
                    'formatter' => ':chili:slider.js.twig'
                )),
                'value_min' => '0',
                'value_max' => '10',
                'multi_color' => true
            ))
        ;
    }
    
    // ...
}
```

### Text Filter

#### Default template

SgDatatablesBundle:Filters:filter_text.html.twig

#### Options

| Option        | Type   | Default |
|---------------|--------|---------|
| search_type   | string | 'like'  |
| property      | string | ''      |
| search_column | string | ''      |
| class         | string | ''      |

#### Example

```php
$this->columnBuilder
    ->add('title', 'column', array(
        'title' => 'Title',
        'filter' => array('text', array(
            'search_type' => 'eq'
        ))
    ))
;
```

### Select Filter

#### Default template

SgDatatablesBundle:Filters:filter_select.html.twig

#### Options

| Option         | Type   | Default |
|----------------|--------|---------|
| search_type    | string | 'like'  |
| property       | string | ''      |
| search_column  | string | ''      |
| class          | string | ''      |
| select_options | array  | array() |

#### Example

```php
$this->columnBuilder
    ->add('visible', 'boolean', array(
        'title' => 'Visible',
        'filter' => array('select', array(
            'search_type' => 'eq',
            'select_options' => array('' => 'Any', '1' => 'Yes', '0' => 'No')
        )),
    ))
;
```

### DateRange Filter

#### Default template

SgDatatablesBundle:Filters:filter_daterange.html.twig

#### Options

| Option         | Type   | Default |
|----------------|--------|---------|
| property       | string | ''      |
| search_column  | string | ''      |
| class          | string | ''      |

#### Example

```php
$this->columnBuilder
    ->add('createdAt', 'datetime', array(
        'title' => 'Created',
        'filter' => array('daterange', array()),
    ))
;
```

### Slider Filter

#### Default template

SgDatatablesBundle:Filters:filter_slider.html.twig

#### Options

| Option             | Type                  | Default      |
|--------------------|-----------------------|--------------|
| search_type        | string                | 'eq'         |
| property           | string                | ''           |
| search_column      | string                | ''           |
| class              | string                | ''           |
| min                | float                 | 0.0          |
| max                | float                 | 10.0         |
| step               | float                 | 1.0          |
| precision          | float                 | 0.0          |
| orientation        | string                | 'horizontal' |
| value              | float or array        | 0.0          |
| range              | bool                  | false        |
| selection          | string                | 'before'     |
| tooltip            | string                | 'show'       |
| tooltip_split      | bool                  | false        |
| tooltip_position   | string or null        | null         |
| handle             | string                | 'round'      |
| reversed           | bool                  | false        |
| enabled            | bool                  | true         |
| formatter          | string                | ''           |
| natural_arrow_keys | bool                  | false        |
| ticks              | array                 | array()      |
| ticks_positions    | array                 | array()      |
| ticks_labels       | array                 | array()      |
| ticks_snap_bounds  | float                 | 0.0          |
| scale              | string                | 'linear'     |
| focus              | bool                  | false        |
| labelledby         | string, array or null | null         |

#### Example

##### Datatable class

```php
$this->columnBuilder
    ->add('heat.id', 'column', array(
        'visible' => false,
        'filter' => array('text', array(
            'search_type' => 'eq'
        ))
    ))
    ->add('heat.heat', 'progress_bar', array(
        'title' => 'Heat',
        'filter' => array('slider', array(
            'min' => 1.0,
            'max' => 12.0,
            'property' => 'heat.id',
            'formatter' => ':chili:slider.js.twig'
        )),
        'value_min' => '0',
        'value_max' => '10',
        'multi_color' => true
    ))
;
```

##### slider.js.twig

```
function formatTooltip(value) {
    if (12 == value) {
        return '10+'
    }

    return value - 1;
}
```
__

## 3. Hide the global search box

The global search box is located in the dom. Remove the `f` option (`f` for filter).

Example:

```php
class PostDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        // ...

        $this->options->set(array(
            'dom' => 'lrtip',
        ));
        
        // ...
    }
    
    // ...
}
```

