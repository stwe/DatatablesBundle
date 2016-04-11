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
                    'search_type' => 'eq'
                ))
            ))
            ->add('visible', 'boolean', array(
                'title' => 'Visible',
                'filter' => array('select', array(
                    'search_type' => 'eq',
                    'select_options' => array('' => 'Any', '1' => 'Yes', '0' => 'No')
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
                'filter' => array('daterange', array())
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
| search_type    | string | 'like'  |
| property       | string | ''      |
| search_column  | string | ''      |

#### Example

```php
$this->columnBuilder
    ->add('createdAt', 'datetime', array(
        'title' => 'Created',
        'filter' => array('daterange', array()),
    ))
;
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

