<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Editable;

/**
 * Interface EditableInterface.
 */
interface EditableInterface
{
    /**
     * @return string
     */
    public function getType();

    /**
     * Checks whether the object may be editable.
     *
     * @return bool
     */
    public function callEditableIfClosure(array $row = []);

    /**
     * @return string
     */
    public function getPk();

    /**
     * @return string
     */
    public function getEmptyText();
}
