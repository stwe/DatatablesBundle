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
 * Interface ColumnInterface
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
interface ColumnInterface
{
    /**
     * Get Property.
     *
     * @return null|string
     */
    public function getProperty();

    /**
     * Set Options.
     *
     * @param array $options
     *
     * @return self
     */
    public function setOptions(array $options);

    /**
     * Set Defaults.
     *
     * @return self
     */
    public function setDefaults();

    /**
     * Get ColumnClassName.
     *
     * @return string
     */
    public function getColumnClassName();
}