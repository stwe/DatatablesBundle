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

/**
 * Class AbstractColumn
 *
 * @package Sg\DatatablesBundle\Column
 */
abstract class AbstractColumn implements ColumnInterface
{
    /**
     * An entity's property.
     *
     * @var null|string
     */
    private $property;

    /**
     * Used to read data from any JSON data source property.
     *
     * @var mixed
     */
    private $mData;

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

    /**
     * Enable or disable the display of this column.
     *
     * @var boolean
     */
    private $bVisible;

    /**
     * The title of this column.
     *
     * @var null|string
     */
    private $sTitle;

    /**
     * This property is the rendering partner to mData
     * and it is suggested that when you want to manipulate data for display.
     *
     * @var null|mixed
     */
    private $mRender;

    /**
     * Class to give to each cell in this column.
     *
     * @var string
     */
    private $sClass;

    /**
     * Allows a default value to be given for a column's data,
     * and will be used whenever a null data source is encountered.
     * This can be because mData is set to null, or because the data
     * source itself is null.
     *
     * @var null|string
     */
    private $sDefaultContent;

    /**
     * Defining the width of the column.
     * This parameter may take any CSS value (em, px etc).
     *
     * @var null|string
     */
    private $sWidth;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param null|string $property An entity's property
     */
    public function __construct($property = null)
    {
        $this->property = $property;
        $this->mData = $property;
        $this->bSearchable = true;
        $this->bSortable = true;
        $this->bVisible = true;
        $this->sTitle = null;
        $this->mRender = null;
        $this->sClass = '';
        $this->sDefaultContent = null;
        $this->sWidth = null;
    }


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperty()
    {
        return $this->property;
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
    public function setSTitle($sTitle)
    {
        $this->sTitle = $sTitle;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSTitle()
    {
        return $this->sTitle;
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

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        if (isset($options['searchable'])) {
            $this->setBSearchable($options['searchable']);
        }
        if (isset($options['sortable'])) {
            $this->setBSortable($options['sortable']);
        }
        if (isset($options['visible'])) {
            $this->setBVisible($options['visible']);
        }
        if (isset($options['title'])) {
            $this->setSTitle($options['title']);
        }
        if (isset($options['render'])) {
            $this->setMRender($options['render']);
        }
        if (isset($options['class'])) {
            $this->setSClass($options['class']);
        }
        if (isset($options['default'])) {
            $this->setSDefaultContent($options['default']);
        }
        if (isset($options['width'])) {
            $this->setSWidth($options['width']);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getClassName();
}