<?php

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
     * @param mixed  $mData The column mData
     * @param string $sName The column sName
     */
    public function __construct($mData = null, $sName = '')
    {
        parent::__construct($mData, $sName);

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
     * @return self
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
     * @return self
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