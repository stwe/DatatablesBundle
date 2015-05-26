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

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\Container;
use Exception;

/**
 * Class AbstractColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
abstract class AbstractColumn implements ColumnInterface, OptionsInterface
{
    /**
     * Column options.
     *
     * @var array
     */
    protected $options;

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
     * Option: class
     *
     * @var string
     */
    protected $class;

    /**
     * Add padding to the text content used when calculating the optimal with for a table.
     * Option: padding
     *
     * @var string
     */
    protected $padding;

    /**
     * Set default, static, content for a column.
     * Option: not in use!
     *
     * @var string
     * @deprecated
     */
    protected $defaultContent;

    /**
     * Set a descriptive name for a column.
     * Option: name
     *
     * @var string
     */
    protected $name;

    /**
     * Enable or disable ordering on this column.
     * Option: orderable
     *
     * @var boolean
     */
    protected $orderable;

    /**
     * Render (process) the data for use in the table.
     * Option: render
     *
     * @var null|string
     */
    protected $render;

    /**
     * Enable or disable filtering on the data in this column.
     * Option: searchable
     *
     * @var boolean
     */
    protected $searchable;

    /**
     * Set the column title.
     * Option: title
     *
     * @var string
     */
    protected $title;

    /**
     * Set the column type - used for filtering and sorting string processing.
     * Option: type
     *
     * @var string
     */
    protected $type;

    /**
     * Enable or disable the display of this column.
     * Option: visible
     *
     * @var boolean
     */
    protected $visible;

    /**
     * Column width assignment.
     * Option: width
     *
     * @var string
     */
    protected $width;

    /**
     * The search type (e.g. "like").
     * Option: search_type
     *
     * @var string
     */
    protected $searchType;

    /**
     * The filter type name ("text" or "select).
     * Option: filter_type
     *
     * @var string
     */
    protected $filterType;

    /**
     * Options for "select" filter type (e.g. "1" => "Yes", "0" => "No").
     * Option: filter_options
     *
     * @var array
     */
    protected $filterOptions;

    /**
     * Filter property: Column name, on which the filter is applied,
     * based on options for this column.
     * Option: filter_property
     *
     * @var string
     */
    protected $filterProperty;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->options = array();
    }

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
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        $methods = get_class_methods($this);

        foreach ($options as $key => $value) {
            $key = Container::camelize($key);
            $method = "set" . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            } else {
                throw new \Exception("setOptions(): {$method} invalid method name");
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setupOptionsResolver(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);

        $this->setOptions($this->options);

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
     * Set class.
     *
     * @param string $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set padding.
     *
     * @param string $padding
     *
     * @return $this
     */
    public function setPadding($padding)
    {
        $this->padding = $padding;

        return $this;
    }

    /**
     * Get padding.
     *
     * @return string
     */
    public function getPadding()
    {
        return $this->padding;
    }

    /**
     * Set default content.
     *
     * @deprecated
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
     * @deprecated
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
     * Set render.
     *
     * @param string $render
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

    /**
     * Set search type.
     *
     * @param string $searchType
     *
     * @return $this
     */
    public function setSearchType($searchType)
    {
        $this->searchType = $searchType;

        return $this;
    }

    /**
     * Get search type.
     *
     * @return string
     */
    public function getSearchType()
    {
        return $this->searchType;
    }

    /**
     * Set filter type.
     *
     * @param string $filterType
     *
     * @return $this
     */
    public function setFilterType($filterType)
    {
        $this->filterType = $filterType;

        return $this;
    }

    /**
     * Get filter type.
     *
     * @return string
     */
    public function getFilterType()
    {
        return $this->filterType;
    }

    /**
     * Set filter options.
     *
     * @param array $filterOptions
     *
     * @return $this
     */
    public function setFilterOptions(array $filterOptions)
    {
        $this->filterOptions = $filterOptions;

        return $this;
    }

    /**
     * Get filter options.
     *
     * @return array
     */
    public function getFilterOptions()
    {
        return $this->filterOptions;
    }

    /**
     * Set filter property.
     *
     * @param string $filterProperty
     *
     * @return $this
     */
    public function setFilterProperty($filterProperty)
    {
        $this->filterProperty = $filterProperty;

        return $this;
    }

    /**
     * Get filter property.
     *
     * @return string
     */
    public function getFilterProperty()
    {
        return $this->filterProperty;
    }
}
