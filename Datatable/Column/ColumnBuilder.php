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
class ColumnBuilder implements ColumnBuilderInterface
{
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

    /**
     * Name of datatable view.
     *
     * @var string
     */
    private $tableName;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param string $tableName
     */
    public function __construct($tableName)
    {
        $this->columns = array();
        $this->multiselectColumn = null;
        $this->multiselect = false;
        $this->tableName = $tableName;
    }

    //-------------------------------------------------
    // ColumnBuilderInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function add($data, $alias, array $options = array())
    {
        // Support embeddables forcing the two backslashes in the column name
        if (strpos($data, '\\') !== false) {
            $data = str_replace('\\', '\\\\', $data);
        }

        /**
         * @var AbstractColumn $column
         */
        $column = ColumnFactory::createColumnByAlias($alias);
        $column->setTableName($this->tableName);
        $column->setData($data);
        $column->setDql($data);
        $column->setupOptionsResolver($options);

        $addColumn = $column->isAddIfClosure();

        if (true === $addColumn) {
            $column->setIndex(count($this->columns));
            $this->columns[] = $column;
        }

        if (true === $addColumn && $column instanceof MultiselectColumn) {
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
    public function removeByData($data)
    {
        if (is_string($data)) {
            foreach ($this->columns as $key => $column) {
                /** @var ColumnInterface $column */
                if ($data === $column->getDql()) {
                    unset($this->columns[$key]);
                    $this->columns = array_values($this->columns);

                    return $this;
                }
            }

            throw new Exception('removeColumnByData(): The column with data ' . $data . ' does not exist.');
        }

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
