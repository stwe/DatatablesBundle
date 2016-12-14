# Filtering

1. [Enable or disable the search abilities](#1-enable-or-disable-the-search-abilities)
2. [Server-side individual column filtering](#2-serverside-individual-column-filtering)
3. [Hide the global search box](#3-hide-the-global-search-box)
4. [Searching on non visible columns](#4-searching-on-non-visible-columns)

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

* [Example](#example)
* [Text Filter](#text-filter)
* [Select Filter](#select-filter)
* [Select2 Filter](#select2-filter)
* [Multiselect Filter](#multiselect-filter)
* [DateRange Filter](#daterange-filter)
* [Slider Filter](#slider-filter)

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
            'individual_filtering_position' => 'foot', // or 'both', default is 'head'
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
                    'cancel_button' => true
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
| cancel_button | bool   | false   |

#### Example

```php
$this->columnBuilder
    ->add('title', 'column', array(
        'title' => 'Title',
        'filter' => array('text', array(
            'search_type' => 'eq'
            'search_column' => 'foo' // initial search example
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
| cancel_button  | bool   | false   |

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

### Select2 Filter

#### Default template

SgDatatablesBundle:Filters:filter_select2.html.twig

#### Options

| Option         | Type             | Default |
|----------------|------------------|---------|
| search_type    | string           | 'like'  |
| property       | string           | ''      |
| search_column  | string           | ''      |
| class          | string           | ''      |
| select_options | array            | array() |
| cancel_button  | bool             | false   |
| multiple       | bool             | true    |
| placeholder    | string or null   | null    |
| allow_clear    | bool             | true    |
| tags           | bool             | true    |
| language       | string           | 'en'    |
| url            | string or null   | null    |
| delay          | integer          | 250     |
| cache          | bool             | true    |

#### Examples

##### Example

```
$fruitcolor = $this->em->getRepository('AppBundle:Fruitcolor')->findAll();
$this->columnBuilder
    ->add('fruitcolor.color', 'column', array(
        'title' => 'Fruchtfarbe',
        'filter' => array('select2', array(
            'select_options' => array('' => 'Alle') + $this->getCollectionAsOptionsArray($fruitcolor, 'color', 'color'),
            'search_type' => 'eq',
            'multiple' => true,
            'placeholder' => null,
            'allow_clear' => true,
            'tags' => true,
            'language' => 'de',
            //'url' => 'select2_color',
            //'delay' => 250,
            //'cache' => true
        )),
    ))
;
```

##### Remote Example

```php
$this->columnBuilder
    ->add('fruitcolor.color', 'column', array(
        'title' => 'Fruchtfarbe',
        'filter' => array('select2', array(
            //'select_options' => array('' => 'Alle') + $this->getCollectionAsOptionsArray($fruitcolor, 'color', 'color'),
            'search_type' => 'eq',
            'multiple' => true,
            'placeholder' => null,
            'allow_clear' => true,
            'tags' => true,
            'language' => 'de',
            'url' => 'select2_color',
            'delay' => 250,
            'cache' => true
        )),
    ))
;
```

```php
/**
 * @param Request $request
 *
 * @Route("/ajax/select2-color", name="select2_color")
 *
 * @return JsonResponse|Response
 */
public function select2Color(Request $request)
{
    if ($request->isXmlHttpRequest()) {
        $em = $this->getDoctrine()->getManager();
        $colors = $em->getRepository('AppBundle:Fruitcolor')->findAll();

        $result = array();

        /** @var \AppBundle\Entity\Fruitcolor $color */
        foreach ($colors as $color) {
            $result[$color->getId()] = $color->getColor();
        }

        return new JsonResponse($result);
    }

    return new Response('Bad request.', 400);
}
```

### Multiselect Filter

#### Default template

SgDatatablesBundle:Filters:filter_multiselect.html.twig

#### Options

| Option         | Type   | Default |
|----------------|--------|---------|
| search_type    | string | 'like'  |
| property       | string | ''      |
| search_column  | string | ''      |
| class          | string | ''      |
| select_options | array  | array() |
| cancel_button  | bool   | false   |

#### Example

```php
$fruitcolor = $this->em->getRepository('AppBundle:Fruitcolor')->findAll();

$this->columnBuilder
    ->add('fruitcolor.color', 'column', array(
        'title' => 'Fruitcolor',
        'filter' => array('multiselect', array(
            'select_options' => array('' => 'All') + $this->getCollectionAsOptionsArray($fruitcolor, 'color', 'color'),
            'search_type' => 'eq'
        )),
    ))
;
```

### DateRange Filter

This Filter relies on the [Bootstrap-Daterangepicker](https://github.com/dangrossman/bootstrap-daterangepicker).

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

This Filter relies on the [Bootstrap-Slider](https://github.com/seiyria/bootstrap-slider).

#### Default template

SgDatatablesBundle:Filters:filter_slider.html.twig

#### Options

| Option             | Type                  | Default      |
|--------------------|-----------------------|--------------|
| search_type        | string                | 'eq'         |
| property           | string                | ''           |
| class              | string                | ''           |
| cancel_button      | bool                  | false        |
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

#### A More Complex Example

##### config.yml

```yaml
# SgDatatablesBundle
sg_datatables:
    datatable:
        query:
            search_on_non_visible_columns: true
```

##### Datatable class

```php
$this->columnBuilder
    ->add('heat.value', 'column', array(
        'visible' => false, // searching on non visible columns must be configured
        'filter' => array('text', array(
            'search_type' => 'eq'
        ))
    ))
    ->add('heat.heat', 'progress_bar', array(
        'title' => 'Heat',
        'filter' => array('slider', array(
            'min' => 0.0,
            'max' => 11.0,
            'cancel_button' => true,
            'property' => 'heat.value',
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
    if (11 == value) {
        return '10+'
    }

    return value;
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

## 4. Searching on non visible columns

```yaml
# SgDatatablesBundle
sg_datatables:
    datatable:
        query:
            search_on_non_visible_columns: true
```
