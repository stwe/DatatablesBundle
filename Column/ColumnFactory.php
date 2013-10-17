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

/**
 * Class ColumnFactory
 *
 * @package Sg\DatatablesBundle\Column
 */
class ColumnFactory
{
    /**
     * @param string $name The name of the column in the entity
     * @param string $id   The id of the column class
     *
     * @throws Exception
     * @return null|ColumnInterface
     */
    public function createbyId($name, $id)
    {
        $column = null;

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
                throw new Exception('Incorrect column class id.');
        }

        return $column;
    }
}