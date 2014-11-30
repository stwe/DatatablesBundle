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

/**
 * Interface ColumnFactoryInterface
 *
 * @package Sg\DatatablesBundle\Datatable\Column
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
