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

use Sg\DatatablesBundle\Datatable\Data\DatatableQuery;

/**
 * Interface ColumnInterface
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
interface ColumnInterface
{
    /**
     * Set data.
     *
     * @param null|string $data
     *
     * @return $this
     */
    public function setData($data);

    /**
     * Set dql.
     *
     * @param null|string $data
     *
     * @return $this
     */
    public function setDql($data);

    /**
     * Get dql.
     *
     * @return null|string
     */
    public function getDql();

    /**
     * Get template.
     *
     * @return string
     */
    public function getTemplate();

    /**
     * Render images or any other special content.
     *
     * @param mixed               $item
     * @param DatatableQuery|null $datatableQuery
     *
     * @return mixed
     */
    public function renderContent(&$item, DatatableQuery $datatableQuery = null);

    /**
     * Get alias.
     *
     * @return string
     */
    public function getAlias();

    /**
     * Check wether an association is given.
     *
     * @return boolean
     */
    public function isAssociation();
}
