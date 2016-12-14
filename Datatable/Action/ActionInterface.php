<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Action;

/**
 * Interface ActionInterface
 *
 * @package Sg\DatatablesBundle\Datatable\Action
 */
interface ActionInterface
{
    /**
     * Set route.
     *
     * @param string $route
     *
     * @return $this
     */
    public function setRoute($route);

    /**
     * Get route.
     *
     * @return string
     */
    public function getRoute();

    /**
     * Checks whether the Action is visible by optional given row data.
     *
     * @param array $row
     *
     * @return boolean
     */
    public function isRenderIfClosure(array $row = array());
}
