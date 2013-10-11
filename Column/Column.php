<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Column;

use Sg\DatatablesBundle\Column\AbstractColumn as BaseColumn;

/**
 * Class Column
 *
 * @package Sg\DatatablesBundle\Column
 */
class Column extends BaseColumn
{
    /**
     * Read data from an array.
     *
     * @var boolean
     */
    protected $renderArray;

    /**
     * Array field name.
     *
     * @var string
     */
    protected $renderArrayFieldName;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param string $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);

        // your own logic

        $this->renderArray = false;
        $this->renderArrayFieldName = 'id';
    }


    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * @param boolean $renderArray
     *
     * @return $this
     */
    public function setRenderArray($renderArray)
    {
        $this->renderArray = $renderArray;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getRenderArray()
    {
        return $this->renderArray;
    }

    /**
     * @param string $renderArrayFieldName
     *
     * @return $this
     */
    public function setRenderArrayFieldName($renderArrayFieldName)
    {
        $this->renderArrayFieldName = $renderArrayFieldName;

        return $this;
    }

    /**
     * @return string
     */
    public function getRenderArrayFieldName()
    {
        return $this->renderArrayFieldName;
    }
}