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
     * An entity's property.
     *
     * @var null|string
     */
    private $property;

    /**
     * Set the data source for the column from the rows data object / array.
     *
     * @var mixed
     */
    private $data;

    /**
     * Enable or disable filtering on the data in this column.
     *
     * @var boolean
     */
    private $searchable;

    /**
     * Enable or disable ordering on this column.
     *
     * @var boolean
     */
    private $orderable;

    /**
     * Enable or disable the display of this column.
     *
     * @var boolean
     */
    private $visible;

    /**
     * Set the column title.
     *
     * @var string
     */
    private $title;

    /**
     * Render (process) the data for use in the table.
     *
     * @var null|mixed
     */
    private $render;

    /**
     * Class to assign to each cell in the column.
     *
     * @var string
     */
    private $className;

    /**
     * Set default, static, content for a column.
     *
     * @var string
     */
    private $defaultContent;

    /**
     * Column width assignment.
     *
     * @var string
     */
    private $width;

    /**
     * Set the column type - used for filtering and sorting string processing.
     *
     * @var string
     */
    private $type;


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
    }


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

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
    public function setOptions(array $options)
    {
        if (array_key_exists("searchable", $options)) {
            $this->setSearchable($options["searchable"]);
        }
        if (array_key_exists("orderable", $options)) {
            $this->setOrderable($options["orderable"]);
        }
        if (array_key_exists("visible", $options)) {
            $this->setVisible($options["visible"]);
        }
        if (array_key_exists("title", $options)) {
            $this->setTitle($options["title"]);
        }
        if (array_key_exists("render", $options)) {
            $this->setRender($options["render"]);
        }
        if (array_key_exists("class", $options)) {
            $this->setClassName($options["class"]);
        }
        if (array_key_exists("default", $options)) {
            $this->setDefaultContent($options["default"]);
        }
        if (array_key_exists("width", $options)) {
            $this->setWidth($options["width"]);
        }
        if (array_key_exists("type", $options)) {
            $this->setWidth($options["type"]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        $this->setData($this->property);
        $this->setSearchable(true);
        $this->setOrderable(true);
        $this->setVisible(true);
        $this->setTitle("");
        $this->setRender(null);
        $this->setClassName("");
        $this->setDefaultContent("");
        $this->setWidth("");
        $this->setType("");
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getColumnClassName();


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set Data.
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get Data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set Searchable.
     *
     * @param boolean $searchable
     *
     * @return $this
     */
    public function setSearchable($searchable)
    {
        $this->searchable = $searchable;

        return $this;
    }

    /**
     * Get Searchable.
     *
     * @return boolean
     */
    public function getSearchable()
    {
        return $this->searchable;
    }

    /**
     * Set Orderable.
     *
     * @param boolean $orderable
     *
     * @return $this
     */
    public function setOrderable($orderable)
    {
        $this->orderable = $orderable;

        return $this;
    }

    /**
     * Get Orderable.
     *
     * @return boolean
     */
    public function getOrderable()
    {
        return $this->orderable;
    }

    /**
     * Set Visible.
     *
     * @param boolean $visible
     *
     * @return $this
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get Visible.
     *
     * @return boolean
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set Title.
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
     * Get Title.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set Render.
     *
     * @param mixed|null $render
     *
     * @return $this
     */
    public function setRender($render)
    {
        $this->render = $render;

        return $this;
    }

    /**
     * Get Render.
     *
     * @return mixed|null
     */
    public function getRender()
    {
        return $this->render;
    }

    /**
     * Set ClassName.
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
     * Get ClassName.
     *
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Set DefaultContent.
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
     * Get DefaultContent.
     *
     * @return string
     */
    public function getDefaultContent()
    {
        return $this->defaultContent;
    }

    /**
     * Set Width.
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
     * Get Width.
     *
     * @return string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set Type.
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
     * Get Type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}