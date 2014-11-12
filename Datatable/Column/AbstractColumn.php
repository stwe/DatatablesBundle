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
     * Contains all allowed options.
     *
     * @var array
     */
    private $allowedOptions;

    /**
     * Class to assign to each cell in the column.
     *
     * @var string
     */
    private $className;

    /**
     * Add padding to the text content used when calculating the optimal with for a table.
     *
     * @var string
     */
    private $contentPadding;

    /**
     * Set the data source for the column from the rows data object / array.
     *
     * @var mixed
     */
    private $data;

    /**
     * Set default, static, content for a column.
     *
     * @var string
     */
    private $defaultContent;

    /**
     * Set a descriptive name for a column.
     *
     * @var string
     */
    private $name;

    /**
     * Enable or disable ordering on this column.
     *
     * @var boolean
     */
    private $orderable;

    /**
     * Render (process) the data for use in the table.
     *
     * @var null|mixed
     */
    private $render;

    /**
     * Enable or disable filtering on the data in this column.
     *
     * @var boolean
     */
    private $searchable;

    /**
     * Set the column title.
     *
     * @var string
     */
    private $title;

    /**
     * Set the column type - used for filtering and sorting string processing.
     *
     * @var string
     */
    private $type;

    /**
     * Enable or disable the display of this column.
     *
     * @var boolean
     */
    private $visible;

    /**
     * Column width assignment.
     *
     * @var string
     */
    private $width;


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
        $this->allowedOptions = array(
            "class", "padding", "default", "name",
            "orderable", "render", "searchable", "title",
            "type", "visible", "width"
        );
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
        $options = array_change_key_case($options, CASE_LOWER);
        $options = array_intersect_key($options, array_flip($this->allowedOptions));

        if (array_key_exists("class", $options)) {
            $this->setClassName($options["class"]);
        }
        if (array_key_exists("padding", $options)) {
            $this->setContentPadding($options["padding"]);
        }
        if (array_key_exists("default", $options)) {
            $this->setDefaultContent($options["default"]);
        }
        if (array_key_exists("name", $options)) {
            $this->setName($options["name"]);
        }
        if (array_key_exists("orderable", $options)) {
            $this->setOrderable($options["orderable"]);
        }
        if (array_key_exists("render", $options)) {
            $this->setRender($options["render"]);
        }
        if (array_key_exists("searchable", $options)) {
            $this->setSearchable($options["searchable"]);
        }
        if (array_key_exists("title", $options)) {
            $this->setTitle($options["title"]);
        }
        if (array_key_exists("type", $options)) {
            $this->setType($options["type"]);
        }
        if (array_key_exists("visible", $options)) {
            $this->setVisible($options["visible"]);
        }
        if (array_key_exists("width", $options)) {
            $this->setWidth($options["width"]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        $this->setClassName("");
        $this->setContentPadding("");
        $this->setData($this->property);
        $this->setDefaultContent("");
        $this->setName("");
        $this->setOrderable(true);
        $this->setRender(null);
        $this->setSearchable(true);
        $this->setTitle("");
        $this->setType("");
        $this->setVisible(true);
        $this->setWidth("");

        return $this;
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Add an allowed option.
     *
     * @param string $option
     *
     * @return $this
     */
    public function addAllowedOption($option)
    {
        $this->allowedOptions[] = $option;

        return $this;
    }

    /**
     * Get allowed options.
     *
     * @return array
     */
    public function getAllowedOptions()
    {
        return $this->allowedOptions;
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
     * Set data.
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
     * Get data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
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
        $this->orderable = $orderable;

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
     * Set render.
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
     * Get render.
     *
     * @return mixed|null
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
        $this->searchable = $searchable;

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
        $this->visible = $visible;

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
