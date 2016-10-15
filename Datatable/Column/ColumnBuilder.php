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

use Exception;

/**
 * Class ColumnBuilder
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ColumnBuilder
{
    /**
     * The columns.
     *
     * @var array
     */
    private $columns;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * ColumnBuilder constructor.
     */
    public function __construct()
    {
        $this->columns = array();
    }

    //-------------------------------------------------
    // Builder
    //-------------------------------------------------

    /**
     * Add Column.
     *
     * @param null|string            $data
     * @param string|ColumnInterface $class
     * @param array                  $options
     *
     * @return $this
     * @throws Exception
     */
    public function add($data, $class, array $options = array())
    {
        /**
         * @var AbstractColumn $column
         */
        $column = ColumnFactory::createColumn($class);
        $column->initOptions(false);
        $column->setData($data);
        $column->setDql($data);
        $column->set($options);

        if (true === $column->callAddIfClosure()) {
            $this->columns[] = $column;
        }

        if (true === $column->isUnique()) {
            // array_count_values(ex.: MultiselectColumn)
            // throw new Exception('add(): There is only one column allowed.')
        }

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
