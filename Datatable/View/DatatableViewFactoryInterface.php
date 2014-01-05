<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\View;

/**
 * Interface DatatableViewFactoryInterface
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
interface DatatableViewFactoryInterface
{
    /**
     * Returns a datatable view.
     *
     * @param string $datatableViewClass The name of the datatable view class
     *
     * @throws \Exception
     * @return AbstractDatatableView
     */
    public function createDatatableView($datatableViewClass);
} 