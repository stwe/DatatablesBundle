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

/**
 * Interface ColumnFactoryInterface
 *
 * @package Wg\UniversalDataTable\DataTable\Column
 */
interface ColumnFactoryInterface
{
    /**
     * Creates a Column by name.
     *
     * @param string|ColumnInterface $name
     *
     * @throws \Symfony\Component\PropertyAccess\Exception\InvalidArgumentException
     * @throws \Exception
     * @return ColumnInterface
     */
    public function createColumnByName($name);
}
