<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable;

use Closure;

trait AddIfTrait
{
    /**
     * Add an object only if conditions are TRUE.
     *
     * @var Closure|null
     */
    protected $addIf;

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Checks whether the object may be added.
     *
     * @return bool
     */
    public function callAddIfClosure()
    {
        if ($this->addIf instanceof Closure) {
            return \call_user_func($this->addIf);
        }

        return true;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return Closure|null
     */
    public function getAddIf()
    {
        return $this->addIf;
    }

    /**
     * @param Closure|null $addIf
     *
     * @return $this
     */
    public function setAddIf($addIf)
    {
        $this->addIf = $addIf;

        return $this;
    }
}
