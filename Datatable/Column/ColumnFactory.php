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

use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class ColumnFactory
 *
 * @package Sg\DatatablesBundle\Datatable\Column
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
            throw new InvalidArgumentException("createColumnByName(): String or ColumnInterface expected.");
        }

        if ($name instanceof ColumnInterface) {
            return $name;
        }

        $name = strtolower($name);

        $this->column = null;

        switch ($name) {
            case "action":
                $this->column = new ActionColumn();
                break;
            case "array":
                $this->column = new ArrayColumn();
                break;
            case "boolean":
                $this->column = new BooleanColumn();
                break;
            case "column":
                $this->column = new Column();
                break;
            case "datetime":
                $this->column = new DateTimeColumn();
                break;
            case "timeago":
                $this->column = new TimeagoColumn();
                break;
            case "virtual":
                $this->column = new VirtualColumn();
                break;
            default:
                throw new InvalidArgumentException("createColumnByName(): The column is not supported.");
        }

        if (null === $this->column) {
            throw new InvalidArgumentException("createColumnByName(): The column could not be created.");
        }

        return $this->column;
    }
}
