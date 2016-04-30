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

use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Interface DatatableViewInterface
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
interface DatatableViewInterface
{
    /**
     * Builds the datatable.
     *
     * @param array $options
     */
    public function buildDatatable(array $options = array());

    /**
     * Get entity manager.
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager();

    /**
     * Get TopActions.
     *
     * @return TopActions
     */
    public function getTopActions();

    /**
     * Get Features.
     *
     * @return Features
     */
    public function getFeatures();

    /**
     * Get Options.
     *
     * @return Options
     */
    public function getOptions();

    /**
     * Get Callbacks.
     *
     * @return Callbacks
     */
    public function getCallbacks();

    /**
     * Get Events.
     *
     * @return Events
     */
    public function getEvents();

    /**
     * Get ColumnBuilder.
     *
     * @return ColumnBuilder
     */
    public function getColumnBuilder();

    /**
     * Get Ajax.
     *
     * @return Ajax
     */
    public function getAjax();

    /**
     * Returns a callable that could transform the data line
     *
     * @return callable
     */
    public function getLineFormatter();

    /**
     * Get custom qb.
     *
     * @return QueryBuilder
     */
    public function getQb();

    /**
     * Returns Entity.
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
