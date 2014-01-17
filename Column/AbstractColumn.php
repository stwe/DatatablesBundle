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

use Exception;

/**
 * Class AbstractColumn
 *
 * @package Sg\DatatablesBundle\Column
 */
abstract class AbstractColumn implements ColumnInterface
{
    /**
     * The name of the column in the entity.
     *
     * @var null|string
     */
    protected $name;

    /**
     * Used to read data from any JSON data source property.
     *
     * @var mixed
     */
    protected $mData;

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
     * The title of this column.
     *
     * @var null|string
     */
    protected $sTitle;

    /**
     * This property is the rendering partner to mData
     * and it is suggested that when you want to manipulate data for display.
     *
     * @var null|mixed
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
     * @var null|string
     */
    protected $sDefaultContent;

    /**
     * Defining the width of the column.
     * This parameter may take any CSS value (em, px etc).
     *
     * @var null|string
     */
    protected $sWidth;


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
        $this->name = $name;
        $this->mData = $name;
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
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return $this->name;
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
        if (true === is_array($sTitle)) {
            if ( !(array_key_exists('label', $sTitle) && array_key_exists('translation_domain', $sTitle)) ) {
                throw new Exception('A label and a translation_domain expected.');
            }
        }

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
}