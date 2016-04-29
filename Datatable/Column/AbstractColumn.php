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

use Sg\DatatablesBundle\Datatable\Data\DatatableQuery;
use Sg\DatatablesBundle\Datatable\View\AbstractViewOptions;
use Sg\DatatablesBundle\OptionsResolver\OptionsInterface;
use Sg\DatatablesBundle\Datatable\Filter\FilterInterface;
use Sg\DatatablesBundle\Datatable\Filter\FilterFactory;

use Symfony\Component\OptionsResolver\OptionsResolver;

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
     *
     * @var string
     */
    protected $class;

    /**
     * Add padding to the text content used when calculating the optimal with for a table.
     *
     * @var string
     */
    protected $padding;

    /**
     * Set default, static, content for a column.
     *
     * @var string
     * @deprecated
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

    /**
     * A Filter instance.
     *
     * @var FilterInterface
     */
    protected $filter;

    /**
     * Name of datatable view.
     *
     * @var string
     */
    protected $tableName;

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

    /**
     * {@inheritdoc}
     */
    public function getDql()
    {
        return $this->dql;
    }

    /**
     * {@inheritdoc}
     */
    public function renderContent(&$item, DatatableQuery $datatableQuery = null)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function isAssociation()
    {
        return (false === strstr($this->data, '.') ? false : true);
    }

    //-------------------------------------------------
    // OptionsResolver
    //-------------------------------------------------

    /**
     * Setup options resolver.
     *
     * @param array $options
     *
     * @return $this
     * @throws \Exception
     */
    public function setupOptionsResolver(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);

        AbstractViewOptions::callingSettersWithOptions($this->options, $this);

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
     * Set Filter instance.
     *
     * @param array $filter
     *
     * @return $this
     */
    public function setFilter(array $filter)
    {
        $filterType = $filter[0];
        $options = $filter[1];

        /** @var \Sg\DatatablesBundle\Datatable\Filter\AbstractFilter $newFilter */
        $newFilter = FilterFactory::createFilterByType($filterType);
        $this->filter = $newFilter->setupOptionsResolver($options);

        return $this;
    }

    /**
     * Get Filter instance.
     *
     * @return FilterInterface
     */
    public function getFilter()
    {
        return $this->filter;
    }

    /**
     * Set table name.
     *
     * @param string $tableName
     *
     * @return $this
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * Get table name.
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }
}
