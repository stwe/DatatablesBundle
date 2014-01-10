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

/**
 * Interface ColumnFactoryInterface
 *
 * @package Sg\DatatablesBundle\Column
 */
interface ColumnFactoryInterface
{
    /**
     * Returns a column.
     *
     * @param string $name The name of the column in the entity
     * @param string $id   The id of the column class
     *
     * @throws \Exception
     * @return null|ColumnInterface
     */
    public function createColumnById($name, $id);
}