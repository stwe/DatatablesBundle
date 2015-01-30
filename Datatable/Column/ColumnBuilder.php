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
 * Class ColumnBuilder
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ColumnBuilder implements ColumnBuilderInterface
{
    /**
     * A ColumnFactoryInterface.
     *
     * @var ColumnFactory
     */
    private $columnFactory;

    /**
     * All columns.
     *
     * @var array
     */
    private $columns;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->columnFactory = new ColumnFactory();
        $this->columns = array();
    }


    //-------------------------------------------------
    // ColumnBuilderInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function add($data, $name, array $options = array())
    {
        /**
         * @var ColumnInterface $column
         */
        $column = $this->columnFactory->createColumnByName($name);
        $column->setData($data);
        $column->setDql($data);
        $column->setDefaults();
        $column->setOptions($options);

        $this->columns[] = $column;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * {@inheritdoc}
     */
    public function getVirtualColumnNames()
    {
        $virtualColumns = array();

        foreach ($this->columns as $column) {
            if ($column instanceof VirtualColumn) {
                $virtualColumns[] = $column->getData();
            }
        }

        return $virtualColumns;
    }
}
