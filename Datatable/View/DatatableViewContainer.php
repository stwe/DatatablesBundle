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
 * Class DatatableViewContainer
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class DatatableViewContainer
{
    /**
     * @var DatatableViewInterface[]
     */
    private $datatables;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->datatables = array();
    }

    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * Add datatable.
     *
     * @param DatatableViewInterface $datatable
     *
     * @return $this
     */
    public function addDatatable(DatatableViewInterface $datatable)
    {
        $this->datatables[$datatable->getName()] = $datatable;

        return $this;
    }

    /**
     * Get datatable by name.
     *
     * @param string $name
     *
     * @return null|DatatableViewInterface
     */
    public function getDatatableByName($name)
    {
        if (array_key_exists($name, $this->datatables)) {
            return $this->datatables[$name];
        }

        return null;
    }
}
