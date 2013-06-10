<?php

namespace Sg\DatatablesBundle\Datatable;

/**
 * Class Field
 *
 * @package Sg\DatatablesBundle\Datatable
 */
class Field
{
    /**
     * Used to read data from any JSON data source property.
     *
     * @var string
     */
    private $mData;

    /**
     * The real name of the database fields.
     *
     * @var string
     */
    private $sName;

    /**
     * Class to give to each cell in this column.
     *
     * @var string
     */
    private $sClass;

    /**
     * Manipulate data for display.
     *
     * @var string
     */
    private $mRender;

    /**
     * Read data from an array.
     *
     * @var boolean
     */
    private $renderArray;

    /**
     * Array field name.
     *
     * @var string
     */
    private $renderArrayFieldName;

    /**
     * Defining the width of the column.
     *
     * @var string
     */
    private $sWidth;

    /**
     * Enable or disable filtering on the data in this column.
     *
     * @var boolean
     */
    private $bSearchable;

    /**
     * Enable or disable sorting on this column.
     *
     * @var boolean
     */
    private $bSortable;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param string $mData
     * @param string $sName
     */
    public function __construct($mData = null, $sName = '')
    {
        $this->mData = $mData;

        if ($sName == '') {
            $this->sName = $mData;
        } else {
            $this->sName = $sName;
        }

        $this->sClass               = '';
        $this->mRender              = null;
        $this->renderArray          = false;
        $this->renderArrayFieldName = 'id';
        $this->bSearchable          = true;
        $this->bSortable            = true;
    }


    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * Set field behavior.
     * These are a few useful field templates.
     *
     * @param string $behavior The field behavior name
     * @param array  $options  The behavior options array
     *
     * @throws \Exception
     * @return Field
     */
    public function setBehavior($behavior, $options = null)
    {
        if ($behavior === 'DetailField') {
            if (!array_key_exists('detail', $options)) {
                throw new \Exception('The option "detail" must be set.');
            };

            $this->setMRender("render_plus_icon(data, type, full)");
            $this->setSClass('details_control');
            $this->setBSortable(false);
            $this->setBSearchable(false);
            $this->setSWidth('2%');
        }

        if ($behavior === 'ActionField') {
            $this->setMRender("render_action_icons(data, type, full)");
            $this->setBSortable(false);
            $this->setBSearchable(false);
            $this->setSWidth('92px');
        }

        if ($behavior === 'BooleanField') {
            $this->setMRender("render_boolean_icons(data, type, full)");
            $this->setBSortable(true);
            $this->setBSearchable(false);
        }

        return $this;
    }

    /**
     * @param string $mData
     *
     * @return Field
     */
    public function setMData($mData)
    {
        $this->mData = $mData;

        return $this;
    }

    /**
     * @return string
     */
    public function getMData()
    {
        return $this->mData;
    }

    /**
     * @param string $sName
     *
     * @return Field
     */
    public function setSName($sName)
    {
        $this->sName = $sName;

        return $this;
    }

    /**
     * @return string
     */
    public function getSName()
    {
        return $this->sName;
    }

    /**
     * @param string $sClass
     *
     * @return Field
     */
    public function setSClass($sClass)
    {
        $this->sClass = $sClass;

        return $this;
    }

    /**
     * @return string
     */
    public function getSClass()
    {
        return $this->sClass;
    }

    /**
     * @param string $mRender
     *
     * @return Field
     */
    public function setMRender($mRender)
    {
        $this->mRender = $mRender;

        return $this;
    }

    /**
     * @return string
     */
    public function getMRender()
    {
        return $this->mRender;
    }

    /**
     * @param boolean $renderArray
     *
     * @return Field
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
     * @return Field
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

    /**
     * @param string $sWidth
     *
     * @return Field
     */
    public function setSWidth($sWidth)
    {
        $this->sWidth = $sWidth;

        return $this;
    }

    /**
     * @return string
     */
    public function getSWidth()
    {
        return $this->sWidth;
    }

    /**
     * @param boolean $bSearchable
     *
     * @return Field
     */
    public function setBSearchable($bSearchable)
    {
        $this->bSearchable = $bSearchable;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getBSearchable()
    {
        return $this->bSearchable;
    }

    /**
     * @param boolean $bSortable
     *
     * @return Field
     */
    public function setBSortable($bSortable)
    {
        $this->bSortable = $bSortable;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getBSortable()
    {
        return $this->bSortable;
    }
}