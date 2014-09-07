<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

/**
 * Interface ColumnFactoryInterface
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
interface ColumnFactoryInterface
{
    /**
     * Creates a Column by name.
     *
     * @param string $property An entity's property
     * @param string $name     The name of the Column class
     *
     * @throws \Symfony\Component\Form\Exception\UnexpectedTypeException
     * @throws \Exception
     * @return ActionColumn|ArrayColumn|BooleanColumn|Column|ColumnInterface|DateTimeColumn|TimeagoColumn
     */
    public function createColumnByName($property, $name);
}
