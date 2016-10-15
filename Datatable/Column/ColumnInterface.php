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
     * Validates $data. Normally a non-empty string is expected.
     *
     * @param mixed $data
     *
     * @return bool
     */
    public function dataConstraint($data);

    /**
     * Specifies whether only a single column of this type is allowed (example: MultiselectColumn).
     *
     * @return bool
     */
    public function isUnique();

    /**
     * Checks wether an association is given.
     *
     * @return bool
     */
    public function isAssociation();

    /**
     * Use the column data value in SELECT statement.
     * Normally is it true. In case of virtual Column, multi select column or data is null is it false.
     *
     * @return bool
     */
    public function isSelectColumn();

    /**
     * Get template.
     *
     * @return string
     */
    public function getTemplate();
}
