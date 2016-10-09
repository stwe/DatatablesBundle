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

use Closure;

/**
 * Class EditableTrait
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
trait EditableTrait
{
    /**
     * Editable flag.
     *
     * @var bool
     */
    protected $editable;

    /**
     * Editable only if conditions are True.
     *
     * @var null|Closure
     */
    protected $editableIf;

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get editable.
     *
     * @return boolean
     */
    public function isEditable()
    {
        return $this->editable;
    }

    /**
     * Set editable.
     *
     * @param boolean $editable
     *
     * @return $this
     */
    public function setEditable($editable)
    {
        $this->editable = $editable;

        return $this;
    }

    /**
     * Get editableIf.
     *
     * @return null|Closure
     */
    public function getEditableIf()
    {
        return $this->editableIf;
    }

    /**
     * Set editableIf.
     *
     * @param null|Closure $editableIf
     *
     * @return $this
     */
    public function setEditableIf($editableIf)
    {
        $this->editableIf = $editableIf;

        return $this;
    }
}
