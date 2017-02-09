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

use Sg\DatatablesBundle\Datatable\Editable\Editable;

/**
 * Class EditableTrait
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
trait EditableTrait
{
    /**
     * Editable Options.
     *
     * @var null|array|Editable
     */
    protected $editable;

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get editable.
     *
     * @return null|array|Editable
     */
    public function getEditable()
    {
        return $this->editable;
    }

    /**
     * Set editable.
     *
     * @param array|null $editable
     *
     * @return $this
     */
    public function setEditable($editable)
    {
        if (is_array($editable)) {
            $newEditable = new Editable();
            $this->editable = $newEditable->set($editable);
        } else {
            $this->editable = $editable;
        }

        return $this;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Get class selector name for editable.
     *
     * @return string
     */
    private function getColumnClassEditableSelector()
    {
        return 'sg-datatables-' . $this->getDatatableName() . '-editable-column-' . $this->index;
    }
}
