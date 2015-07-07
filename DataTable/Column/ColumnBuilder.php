<?php

/**
 * This file is part of the WgUniversalDataTableBundle package.
 *
 * (c) stwe <https://github.com/stwe/DataTablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wg\UniversalDataTable\DataTable\Column;

use Exception;

/**
 * Class ColumnBuilder
 *
 * @package Wg\UniversalDataTable\DataTable\Column
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

    /**
     * The multiselect column.
     *
     * @var ColumnInterface
     */
    private $multiselectColumn;

    /**
     * Multiselect column flag.
     *
     * @var boolean
     */
    private $multiselect;

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
        $this->multiselectColumn = null;
        $this->multiselect = false;
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
         * @var AbstractColumn $column
         */
        $column = $this->columnFactory->createColumnByName($name);
        $column->setData($data);
        $column->setDql($data);
        $column->setupOptionsResolver($options);

        $this->columns[] = $column;

        if ($column instanceof MultiselectColumn) {
            if (false === $this->multiselect) {
                $this->multiselect = true;
                $this->multiselectColumn = $column;
            } else {
                throw new Exception('add(): There is only one multiselect column allowed.');
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function removeByKey($key)
    {
        if (is_int($key) && array_key_exists($key, $this->columns)) {
            unset($this->columns[$key]);
        } else {
            throw new Exception('removeColumnByKey(): The array key ' . $key . ' does not exist.');
        }

        $this->columns = array_values($this->columns);

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
    public function getVirtualColumns()
    {
        $virtualColumns = array();

        foreach ($this->columns as $column) {
            if ($column instanceof VirtualColumn) {
                $virtualColumns[] = $column->getData();
            }
        }

        return $virtualColumns;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get multiselect column.
     *
     * @return ColumnInterface
     */
    public function getMultiselectColumn()
    {
        return $this->multiselectColumn;
    }

    /**
     * Is multiselect.
     *
     * @return boolean
     */
    public function isMultiselect()
    {
        return (boolean) $this->multiselect;
    }
}
