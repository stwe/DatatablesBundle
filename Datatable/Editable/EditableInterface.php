<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Editable;

/**
 * Interface EditableInterface
 *
 * @package Sg\DatatablesBundle\Datatable\Editable
 */
interface EditableInterface
{
    /**
     * Get type.
     *
     * @return string
     */
    public function getType();

    /**
     * Checks whether the object may be editable.
     *
     * @param array $row
     *
     * @return bool
     */
    public function callEditableIfClosure(array $row = array());

    /**
     * Get pk.
     *
     * @return string
     */
    public function getPk();

    /**
     * Get emptyText.
     *
     * @return string
     */
    public function getEmptyText();
}
