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

use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class ColumnFactory
 *
 * @package Wg\UniversalDataTable\DataTable\Column
 */
class ColumnFactory implements ColumnFactoryInterface
{
    /**
     * A ColumnInterface.
     *
     * @var ColumnInterface
     */
    private $column;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->column = null;
    }

    //-------------------------------------------------
    // ColumnFactoryInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function createColumnByName($name)
    {
        if (empty($name) || !is_string($name) && !$name instanceof ColumnInterface) {
            throw new InvalidArgumentException('createColumnByName(): String or ColumnInterface expected.');
        }

        if ($name instanceof ColumnInterface) {
            return $name;
        }

        $name = strtolower($name);

        $this->column = null;

        switch ($name) {
            case 'action':
                $this->column = new ActionColumn();
                break;
            case 'array':
                $this->column = new ArrayColumn();
                break;
            case 'boolean':
                $this->column = new BooleanColumn();
                break;
            case 'column':
                $this->column = new Column();
                break;
            case 'datetime':
                $this->column = new DateTimeColumn();
                break;
            case 'timeago':
                $this->column = new TimeagoColumn();
                break;
            case 'multiselect':
                $this->column = new MultiselectColumn();
                break;
            case 'virtual':
                $this->column = new VirtualColumn();
                break;
            default:
                throw new InvalidArgumentException('createColumnByName(): The column is not supported.');
        }

        if (null === $this->column) {
            throw new InvalidArgumentException('createColumnByName(): The column could not be created.');
        }

        return $this->column;
    }
}
