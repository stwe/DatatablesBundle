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
 * Interface ColumnBuilderInterface
 *
 * @package Wg\UniversalDataTable\DataTable\Column
 */
interface ColumnBuilderInterface
{
    /**
     * Add a ColumnInterface.
     *
     * @param null|string            $data    The data source for the column
     * @param string|ColumnInterface $name    Column class alias or instance of ColumnInterface
     * @param array                  $options The column options
     *
     * @return $this
     */
    public function add($data, $name, array $options = array());

    /**
     * Remove column by key.
     *
     * @param integer $key
     *
     * @return $this
     * @throws \Exception
     */
    public function removeByKey($key);

    /**
     * Get all columns.
     *
     * @return array
     */
    public function getColumns();

    /**
     * Get all virtual columns.
     *
     * @return array
     */
    public function getVirtualColumns();
}
