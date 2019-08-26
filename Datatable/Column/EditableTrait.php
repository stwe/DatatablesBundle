<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Exception;
use Sg\DatatablesBundle\Datatable\Editable\EditableInterface;
use Sg\DatatablesBundle\Datatable\Factory;

trait EditableTrait
{
    /**
     * An EditableInterface instance.
     * Default: null.
     *
     * @var EditableInterface|null
     */
    protected $editable;

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return EditableInterface|null
     */
    public function getEditable()
    {
        return $this->editable;
    }

    /**
     * @param array|null $editableClassAndOptions
     *
     * @throws Exception
     *
     * @return $this
     */
    public function setEditable($editableClassAndOptions)
    {
        if (\is_array($editableClassAndOptions)) {
            if (2 !== \count($editableClassAndOptions)) {
                throw new Exception('EditableTrait::setEditable(): Two arguments expected.');
            }

            if (! isset($editableClassAndOptions[0]) || ! \is_string($editableClassAndOptions[0]) && ! $editableClassAndOptions[0] instanceof EditableInterface) {
                throw new Exception('EditableTrait::setEditable(): Set a Editable class.');
            }

            if (! isset($editableClassAndOptions[1]) || ! \is_array($editableClassAndOptions[1])) {
                throw new Exception('EditableTrait::setEditable(): Set an options array.');
            }

            $newEditable = Factory::create($editableClassAndOptions[0], EditableInterface::class);
            $this->editable = $newEditable->set($editableClassAndOptions[1]);
        } else {
            $this->editable = $editableClassAndOptions;
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
    protected function getColumnClassEditableSelector()
    {
        return 'sg-datatables-'.$this->getDatatableName().'-editable-column-'.$this->index;
    }
}
