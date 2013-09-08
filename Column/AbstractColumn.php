<?php

namespace Sg\DatatablesBundle\Column;

/**
 * Class AbstractColumn
 *
 * @package Sg\DatatablesBundle\Column
 */
abstract class AbstractColumn implements ColumnInterface
{
    /**
     * Enable or disable filtering on the data in this column.
     *
     * @var boolean
     */
    protected $bSearchable;

    /**
     * Enable or disable sorting on this column.
     *
     * @var boolean
     */
    protected $bSortable;

    /**
     * Enable or disable the display of this column.
     *
     * @var boolean
     */
    protected $bVisible;

    /**
     * Used to read data from any JSON data source property.
     *
     * @var mixed
     */
    protected $mData;

    /**
     * This property is the rendering partner to mData
     * and it is suggested that when you want to manipulate data for display.
     *
     * @var mixed
     */
    protected $mRender;

    /**
     * Class to give to each cell in this column.
     *
     * @var string
     */
    protected $sClass;

    /**
     * Allows a default value to be given for a column's data,
     * and will be used whenever a null data source is encountered.
     * This can be because mData is set to null, or because the data
     * source itself is null.
     *
     * @var string
     */
    protected $sDefaultContent;

    /**
     * The real name of the database field.
     *
     * @var string
     */
    protected $sName;

    /**
     * Defining the width of the column.
     *
     * @var string
     */
    protected $sWidth;


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
        $this->bSearchable = true;
        $this->bSortable = true;
        $this->bVisible = true;

        $this->mData = $mData;
        $this->mRender = null;

        $this->sClass = '';
        $this->sDefaultContent = null;

        if ($sName == '') {
            $this->sName = $mData;
        } else {
            $this->sName = $sName;
        }

        $this->sWidth = null;
    }


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setBSearchable($bSearchable)
    {
        $this->bSearchable = $bSearchable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBSearchable()
    {
        return $this->bSearchable;
    }

    /**
     * {@inheritdoc}
     */
    public function setBSortable($bSortable)
    {
        $this->bSortable = $bSortable;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBSortable()
    {
        return $this->bSortable;
    }

    /**
     * {@inheritdoc}
     */
    public function setBVisible($bVisible)
    {
        $this->bVisible = $bVisible;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getBVisible()
    {
        return $this->bVisible;
    }

    /**
     * {@inheritdoc}
     */
    public function setMData($mData)
    {
        $this->mData = $mData;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMData()
    {
        return $this->mData;
    }

    /**
     * {@inheritdoc}
     */
    public function setMRender($mRender)
    {
        $this->mRender = $mRender;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getMRender()
    {
        return $this->mRender;
    }

    /**
     * {@inheritdoc}
     */
    public function setSClass($sClass)
    {
        $this->sClass = $sClass;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSClass()
    {
        return $this->sClass;
    }

    /**
     * {@inheritdoc}
     */
    public function setSDefaultContent($sDefaultContent)
    {
        $this->sDefaultContent = $sDefaultContent;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSDefaultContent()
    {
        return $this->sDefaultContent;
    }

    /**
     * {@inheritdoc}
     */
    public function setSName($sName)
    {
        $this->sName = $sName;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSName()
    {
        return $this->sName;
    }

    /**
     * {@inheritdoc}
     */
    public function setSWidth($sWidth)
    {
        $this->sWidth = $sWidth;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSWidth()
    {
        return $this->sWidth;
    }
}