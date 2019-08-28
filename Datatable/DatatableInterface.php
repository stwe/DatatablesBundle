<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable;

use Doctrine\ORM\EntityManagerInterface;
use Sg\DatatablesBundle\Datatable\Column\ColumnBuilder;

/**
 * Interface DatatableInterface.
 */
interface DatatableInterface
{
    const NAME_REGEX = '/[a-zA-Z0-9\-\_]+/';

    /**
     * Builds the datatable.
     */
    public function buildDatatable(array $options = []);

    /**
     * Returns a callable that modify the data row.
     *
     * @return callable
     */
    public function getLineFormatter();

    /**
     * @return ColumnBuilder
     */
    public function getColumnBuilder();

    /**
     * Get Ajax instance.
     *
     * @return Ajax
     */
    public function getAjax();

    /**
     * Get Options instance.
     *
     * @return Options
     */
    public function getOptions();

    /**
     * Get Features instance.
     *
     * @return Features
     */
    public function getFeatures();

    /**
     * Get Callbacks instance.
     *
     * @return Callbacks
     */
    public function getCallbacks();

    /**
     * Get Events instance.
     *
     * @return Events
     */
    public function getEvents();

    /**
     * Get Extensions instance.
     *
     * @return Extensions
     */
    public function getExtensions();

    /**
     * Get Language instance.
     *
     * @return Language
     */
    public function getLanguage();

    /**
     * Get the EntityManager.
     *
     * @return EntityManagerInterface
     */
    public function getEntityManager();

    /**
     * Help function to create an option array for filtering.
     *
     * @param array  $entities
     * @param string $keyFrom
     * @param string $valueFrom
     *
     * @return array
     */
    public function getOptionsArrayFromEntities($entities, $keyFrom = 'id', $valueFrom = 'name');

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

    /**
     * Returns the unique id of this datatable view.
     *
     * @return int
     */
    public function getUniqueId();

    /**
     * Returns the unique name of this datatable view.
     *
     * @return string
     */
    public function getUniqueName();
}
