# How to use the LineFormatter

The purpose is to format line directly by passing a closure in the DatatableView
class. The transformer modificate data after that orders and filters are applied.
Used in thinking that to alter data couldn't change originals orders or filters.

This function permit by example to centralize datatables operations and to
securize request by removing id and transform it in a slug.

1. [Example](#1-example)
2. [Using repositories](#2-using-repositories)

## 1. Example

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

## 2. Using repositories

### Repository function

```
public function getPlantSum($seasonId)
{
    return $this->createQueryBuilder('p')
        ->select('SUM(p.quantity)')
        ->join('p.season', 's')
        ->where('s = :seasonId')
        ->setParameter('seasonId', $seasonId)
        ->getQuery()
        ->getSingleScalarResult();
}
```

### Line Formatter function

```
public function getLineFormatter()
{
    $repository = $this->em->getRepository('AppBundle:Plant');

    $formatter = function($line) use ($repository) {
        $sum = $repository->getPlantSum($line['id']);
        null === $sum ? $line['allPlants'] = 0 : $line['allPlants'] = $sum;

        return $line;
    };

    return $formatter;
}
```
