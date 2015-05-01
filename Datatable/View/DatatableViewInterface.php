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

use Sg\DatatablesBundle\Datatable\Column\ColumnBuilder;

/**
 * Interface DatatableViewInterface
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
interface DatatableViewInterface
{
    /**
     * Builds the datatable view.
     */
    public function buildDatatableView();

    /**
     * Renders the datatable view.
     *
     * @param string $type
     *
     * @return mixed
     * @throws \Exception
     * @throws \Twig_Error
     */
    public function render($type = "all");

    /**
     * Get Ajax.
     *
     * @return Ajax
     */
    public function getAjax();

    /**
     * Get ColumnBuilder.
     *
     * @return ColumnBuilder
     */
    public function getColumnBuilder();

    /**
     * Returns a callable that could transform the data line
     *
     * @return callable
     */
    public function getLineFormatter();

    /**
     * Returns Entity.
     *
     * @return string
     */
    public function getEntity();

    /**
     * Returns the name of this datatable view.
     * Is used as jQuery datatable id selector.
     *
     * @return string
     */
    public function getName();
}
