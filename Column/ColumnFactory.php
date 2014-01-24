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

use Exception;
use Symfony\Component\Form\Exception\UnexpectedTypeException;

/**
 * Class ColumnFactory
 *
 * @package Sg\DatatablesBundle\Column
 */
class ColumnFactory implements ColumnFactoryInterface
{
    private $column = null;


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
                throw new UnexpectedTypeException($property, 'A string or null expected.');
            }
        }

        if (!is_string($name)) {
            throw new UnexpectedTypeException($name, 'A string is expected.');
        }

        $name = strtolower($name);

        switch ($name) {
            case 'action':
                $this->column = new ActionColumn($property);
                break;
            case 'array':
                $this->column = new ArrayColumn($property);
                break;
            case 'boolean':
                $this->column = new BooleanColumn($property);
                break;
            case 'column':
                $this->column = new Column($property);
                break;
            case 'datetime':
                $this->column = new DateTimeColumn($property);
                break;
            default:
                throw new Exception('There was no column class to be created.');
        }

        return $this->column;
    }
}