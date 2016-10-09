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

/**
 * Interface ColumnInterface
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
interface ColumnInterface
{
    /**
     * Checks whether the column may be added.
     *
     * @return bool
     */
    public function callAddIfClosure();

    /**
     * Specifies whether only a single column of this type is allowed.
     *
     * @return bool
     */
    public function getUnique();

    /**
     * Get template.
     *
     * @return string
     */
    public function getTemplate();
}
