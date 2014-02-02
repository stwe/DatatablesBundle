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
 * Interface MultiselectInterface
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
interface MultiselectInterface
{
    /**
     * Add action.
     *
     * @param string $title The title for the form select field
     * @param string $route The route of the action
     *
     * @return $this
     */
    public function addAction($title, $route);

    /**
     * Get actions.
     *
     * @return array
     */
    public function getActions();

    /**
     * Set enabled.
     *
     * @param boolean $enabled
     *
     * @return $this
     */
    public function setEnabled($enabled);

    /**
     * Get enabled.
     *
     * @return boolean
     */
    public function getEnabled();

    /**
     * Set position.
     *
     * @param string $position
     *
     * @return $this
     * @throws \Exception
     */
    public function setPosition($position);

    /**
     * Get position.
     *
     * @return string
     */
    public function getPosition();
}