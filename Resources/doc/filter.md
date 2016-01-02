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

## 2. ServerSide individual column filtering

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
            ->add('visible', 'column', array(
                'title' => 'Visible',
                'filter_type' => 'select',
                'filter_options' => array('' => 'Any', '0' => 'No', '1' => 'Yes'),
                'filter_property' => 'visible',
            ))
            ->add('createdby.username', 'column', array(
                'title' => 'Createdby',
                'filter_type' => 'select', // Render the search input as a dropdown.
                'filter_options' => array('' => 'All') + $this->getCollectionAsOptionsArray($users, 'username', 'username'), // Dropdown options list. This method should return all options as array [username => username].
                'filter_property' => 'createdby.username', // You can set up another property, different with the current column, to search on.
            ))
        ;
    }
    
    // ...
}
```

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

