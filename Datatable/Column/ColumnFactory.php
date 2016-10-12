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
 * Class ColumnFactory
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ColumnFactory
{
    /**
     * Create Column.
     *
     * @param string|ColumnInterface $class
     *
     * @return ColumnInterface
     * @throws Exception
     */
    public static function createColumn($class)
    {
        if (empty($class) || !is_string($class) && !$class instanceof ColumnInterface) {
            throw new Exception('ColumnFactory::createColumn(): String or ColumnInterface expected.');
        }

        if ($class instanceof ColumnInterface) {
            return $class;
        }

        if (is_string($class) && class_exists($class)) {
            $column = new $class;

            if (!$column instanceof ColumnInterface) {
                throw new Exception('ColumnFactory::createColumn(): ColumnInterface expected.');
            } else {
                return $column;
            }
        } else {
            throw new Exception("ColumnFactory::createColumn(): $class is not callable.");
        }
    }
}
