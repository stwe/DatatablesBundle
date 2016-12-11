<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable;

use Closure;

/**
 * Class RenderIfTrait
 *
 * @package Sg\DatatablesBundle\Datatable
 */
trait RenderIfTrait
{
    /**
     * Render an object only if conditions are TRUE.
     *
     * @var null|Closure
     */
    protected $renderIf;

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Checks whether the object may be added.
     *
     * @param array $row
     *
     * @return bool
     */
    public function callRenderIfClosure(array $row = array())
    {
        if ($this->renderIf instanceof Closure) {
            return call_user_func($this->renderIf, $row);
        }

        return true;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get renderIf.
     *
     * @return null|Closure
     */
    public function getRenderIf()
    {
        return $this->renderIf;
    }

    /**
     * Set renderIf.
     *
     * @param null|Closure $renderIf
     *
     * @return $this
     */
    public function setRenderIf($renderIf)
    {
        $this->renderIf = $renderIf;

        return $this;
    }
}
