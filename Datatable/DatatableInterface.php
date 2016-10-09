<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable;

/**
 * Interface DatatableInterface
 *
 * @package Sg\DatatablesBundle\Datatable
 */
interface DatatableInterface
{
    /**
     * Builds the datatable.
     *
     * @param array $options
     */
    public function buildDatatable(array $options = array());

    /**
     * Get all generated Columns.
     *
     * @return array
     */
    public function getColumns();

    /**
     * Returns the name of the entity.
     *
     * @return string
     */
    public function getEntity();

    /**
     * Returns the name of this datatable view.
     *
     * @return string
     */
    public function getName();
}
