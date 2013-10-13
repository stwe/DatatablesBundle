<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Column;

/**
 * Class ColumnBuilder
 *
 * @package Sg\DatatablesBundle\Column
 */
class ColumnBuilder
{
    /**
     * @var ColumnFactory
     */
    private $columnFactory;

    /**
     * @var array
     */
    private $columns;


    /**
     * Ctor.
     *
     * @param ColumnFactory $columnFactory
     */
    public function __construct(ColumnFactory $columnFactory)
    {
        $this->columnFactory = $columnFactory;
        $this->columns = array();
    }

    /**
     * Add a ColumnInterface.
     *
     * @param string $name    The name of the column in the entity
     * @param string $id      The id of the column class
     * @param array  $options The column options
     *
     * @return $this
     */
    public function add($name, $id, array $options = array())
    {
        /**
         * @var ColumnInterface $column
         */
        $column = $this->columnFactory->createbyId($name, $id);
        $column->setOptions($options);

        $this->columns[] = $column;

        return $this;
    }

    /**
     * Get columns.
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }
}