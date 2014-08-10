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
use Symfony\Component\Form\Exception\UnexpectedTypeException;

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
    public function createColumnByName($property, $name)
    {
        if (!is_string($property)) {
            if (!is_null($property)) {
                throw new UnexpectedTypeException($property, "A string or null expected.");
            }
        }

        if (!is_string($name)) {
            throw new UnexpectedTypeException($name, "A string is expected.");
        }

        $name = strtolower($name);

        $this->column = null;

        switch ($name) {
            case "action":
                $this->column = new ActionColumn($property);
                break;
            case "array":
                $this->column = new ArrayColumn($property);
                break;
            case "boolean":
                $this->column = new BooleanColumn($property);
                break;
            case "column":
                $this->column = new Column($property);
                break;
            case "datetime":
                $this->column = new DateTimeColumn($property);
                break;
            case "timeago":
                $this->column = new TimeagoColumn($property);
                break;
            case "virtual":
                $this->column = new VirtualColumn($property);
                break;            
            default:
                throw new Exception("The {$name} column is not supported.");
        }

        if (null === $this->column) {
            throw new Exception("The column could not be created.");
        }

        return $this->column;
    }
}
