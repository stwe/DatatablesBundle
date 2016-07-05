# How to use the LineFormatter

The purpose is to format line directly by passing a closure in the DatatableView
class. The transformer modificate data after that orders and filters are applied.
Used in thinking that to alter data couldn't change originals orders or filters.

This function permit by example to centralize datatables operations and to
securize request by removing id and transform it in a slug.

## Example

```php
<?php

namespace Sg\BlogBundle\Datatables;

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;

/**
 * Class UserDatatable
 *
 * @package Sg\BlogBundle\Datatables
 */
class UserDatatable extends AbstractDatatableView
{

    /**
     * {@inheritdoc}
     */
    public function getLineFormatter()
    {
        $formatter = function($line){
            $line['name'] = $line['firstName'] . ', ' . $line['lastName'];

            return $line;
        };

        return $formatter;
    }

    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        //...

        $this->columnBuilder
            ->add('firstName', 'column', array('visible' => false))
            ->add('lastName', 'column', array('visible' => false))
            ->add('name', 'virtual')

            //....
        ;
        // ...
    }

}
```
