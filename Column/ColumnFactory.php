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
    //-------------------------------------------------
    // ColumnFactoryInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function createColumnById($name, $id)
    {
        $column = null;

        if (!is_string($name)) {
            throw new UnexpectedTypeException($name, 'string expected');
        }

        if (!is_string($id)) {
            throw new UnexpectedTypeException($id, 'string expected');
        }

        switch ($id) {
            case 'action':
                $column = new ActionColumn($name);
                break;
            case 'array':
                $column = new ArrayColumn($name);
                break;
            case 'boolean':
                $column = new BooleanColumn($name);
                break;
            case 'column':
                $column = new Column($name);
                break;
            case 'datetime':
                $column = new DateTimeColumn($name);
                break;
            default:
                throw new Exception('Incorrect class id.');
        }

        return $column;
    }
}