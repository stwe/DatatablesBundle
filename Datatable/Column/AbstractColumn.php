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

/**
 * Class AbstractColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
abstract class AbstractColumn implements ColumnInterface
{
    /**
     * Set the data source for the column from the rows data object / array.
     *
     * @var null|string
     */
    protected $data;

    /**
     * Data source copy.
     *
     * @var null|string
     */
    protected $dql;

    /**
     * Class to assign to each cell in the column.
     *
     * @var string
     */
    protected $className;

    /**
     * Add padding to the text content used when calculating the optimal with for a table.
     *
     * @var string
     */
    protected $contentPadding;

    /**
     * Set default, static, content for a column.
     *
     * @var string
     */
    protected $defaultContent;

    /**
     * Set a descriptive name for a column.
     *
     * @var string
     */
    protected $name;

    /**
     * Enable or disable ordering on this column.
     *
     * @var boolean
     */
    protected $orderable;

    /**
     * Render (process) the data for use in the table.
     *
     * @var null|string
     */
    protected $render;

    /**
     * Enable or disable filtering on the data in this column.
     *
     * @var boolean
     */
    protected $searchable;

    /**
     * Set the column title.
     *
     * @var string
     */
    protected $title;

    /**
     * Set the column type - used for filtering and sorting string processing.
     *
     * @var string
     */
    protected $type;

    /**
     * Enable or disable the display of this column.
     *
     * @var boolean
     */
    protected $visible;

    /**
     * Column width assignment.
     *
     * @var string
     */
    protected $width;


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setDql($dql)
    {
        $this->dql = $dql;

        return $this;
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get data.
     *
     * @return null|string
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Get dql.
     *
     * @return null|string
     */
    public function getDql()
    {
        return $this->dql;
    }

    /**
     * Set class name.
     *
     * @param string $className
     *
     * @return $this
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get class name.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Set content padding.
     *
     * @param string $contentPadding
     *
     * @return $this
     */
    public function setContentPadding($contentPadding)
    {
        $this->contentPadding = $contentPadding;

        return $this;
    }

    /**
     * Get content padding.
     *
     * @return string
     */
    public function getContentPadding()
    {
        return $this->contentPadding;
    }

    /**
     * Set default content.
     *
     * @param string $defaultContent
     *
     * @return $this
     */
    public function setDefaultContent($defaultContent)
    {
        $this->defaultContent = $defaultContent;

        return $this;
    }

    /**
     * Get default content.
     *
     * @return string
     */
    public function getDefaultContent()
    {
        return $this->defaultContent;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set orderable.
     *
     * @param boolean $orderable
     *
     * @return $this
     */
    public function setOrderable($orderable)
    {
        $this->orderable = (boolean) $orderable;

        return $this;
    }

    /**
     * Get orderable.
     *
     * @return boolean
     */
    public function getOrderable()
    {
        return $this->orderable;
    }

    /**
     * Get render.
     *
     * @return null|string
     */
    public function getRender()
    {
        return $this->render;
    }

    /**
     * Set searchable.
     *
     * @param boolean $searchable
     *
     * @return $this
     */
    public function setSearchable($searchable)
    {
        $this->searchable = (boolean) $searchable;

        return $this;
    }

    /**
     * Get searchable.
     *
     * @return boolean
     */
    public function getSearchable()
    {
        return $this->searchable;
    }

    /**
     * Set title.
     *
     * @param string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set visible.
     *
     * @param boolean $visible
     *
     * @return $this
     */
    public function setVisible($visible)
    {
        $this->visible = (boolean) $visible;

        return $this;
    }

    /**
     * Get visible.
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set width.
     *
     * @param string $width
     *
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get width.
     *
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }
}
