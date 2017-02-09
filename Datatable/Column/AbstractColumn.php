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

use Sg\DatatablesBundle\Datatable\OptionsTrait;
use Sg\DatatablesBundle\Datatable\AddIfTrait;
use Sg\DatatablesBundle\Datatable\Filter\FilterFactory;
use Sg\DatatablesBundle\Datatable\Filter\FilterInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Twig_Environment;
use Exception;

/**
 * Class AbstractColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
abstract class AbstractColumn implements ColumnInterface
{
    /**
     * Use the OptionsResolver.
     */
    use OptionsTrait;

    /**
     * Use an 'add_if' option to check in ColumnBuilder if the Column can be added.
     */
    use AddIfTrait;

    //--------------------------------------------------------------------------------------------------
    // DataTables - Columns Options
    // ----------------------------
    // All Column Options are initialized with 'null' - except 'searchable', 'orderable', and 'visible'.
    // These 'null' initialized options uses the default value of the DataTables plugin.
    // 'searchable', 'orderable', and 'visible' are required in the QueryBuilder and are therefore
    // pre-assigned with a value (true or false).
    //--------------------------------------------------------------------------------------------------

    /**
     * Change the cell type created for the column - either TD cells or TH cells.
     * DataTables default: td
     * Default: null
     *
     * @var null|string
     */
    protected $cellType;

    /**
     * Adds a class to each cell in a column.
     * Default: null
     *
     * @var null|string
     */
    protected $className;

    /**
     * Add padding to the text content used when calculating the optimal with for a table.
     * Default: null
     *
     * @var null|string
     */
    protected $contentPadding;

    /**
     * Set the data source for the column from the rows data object / array.
     * DataTables default: Takes the index value of the column automatically.
     *
     * This property has normally the same value as $this->dql.
     * Is set in the ColumnBuilder.
     *
     * @var null|string
     */
    protected $data;

    /**
     * Set default, static, content for a column.
     * Default: null
     *
     * @var null|string
     */
    protected $defaultContent;

    /**
     * Set a descriptive name for a column.
     * Default: null
     *
     * @var null|string
     */
    protected $name;

    /**
     * Enable or disable ordering on this column.
     * DataTables default: true
     * Default: true
     *
     * @var bool
     */
    protected $orderable;

    /**
     * Define multiple column ordering as the default order for a column.
     * DataTables default: Takes the index value of the column automatically.
     * Default: null
     *
     * @var null|int|array
     */
    protected $orderData;

    /**
     * Order direction application sequence.
     * DataTables default: ['asc', 'desc']
     * Default: null
     *
     * @var null|array
     */
    protected $orderSequence;

    /**
     * Enable or disable filtering on the data in this column.
     * DataTables default: true
     * Default: true
     *
     * @var bool
     */
    protected $searchable;

    /**
     * Set the column title.
     * DataTables default: Value read from the column's header cell.
     * Default: null
     *
     * @var null|string
     */
    protected $title;

    /**
     * Enable or disable the display of this column.
     * DataTables default: true
     * Default: true
     *
     * @var bool
     */
    protected $visible;

    /**
     * Column width assignment.
     * DataTables default: Auto-detected from the table's content.
     * Default: null
     *
     * @var null|string
     */
    protected $width;

    //-------------------------------------------------
    // Custom Options
    //-------------------------------------------------

    /**
     * Join type (default: 'leftJoin'), if the column represents an association.
     * Default: 'leftJoin'
     *
     * @var string
     */
    protected $joinType;

    /**
     * The data type of the column.
     * Default: null
     *
     * @var null|string
     */
    protected $typeOfField;

    /**
     * A Filter instance for individual filtering.
     * Default: See the different column types.
     *
     * @var FilterInterface
     */
    protected $filter;

    //-------------------------------------------------
    // Other Properties
    //-------------------------------------------------

    /**
     * The first argument of ColumnBuilders 'add' function.
     * The DatatableQuery class works with this property.
     * Is set in the ColumnBuilder.
     *
     * @var null|string
     */
    protected $dql;

    /**
     * The Twig Environment to render Twig templates in Column rowes.
     * Is set in the ColumnBuilder.
     *
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * The position in the Columns array.
     * Is set in the ColumnBuilder.
     *
     * @var int
     */
    protected $index;

    /**
     * The name of the associated Datatable.
     * Is set in the ColumnBuilder.
     *
     * @var string
     */
    protected $datatableName;

    /**
     * The fully-qualified class name of the entity.
     *
     * @var string
     */
    protected $entityClassName;

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * Config options.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // the 'data' option needs no default value
        $resolver->setDefined('data');

        $resolver->setDefaults(array(
            'cell_type' => null,
            'class_name' => null,
            'content_padding' => null,
            'default_content' => null,
            'name' => null,
            'orderable' => true,
            'order_data' => null,
            'order_sequence' => null,
            'searchable' => true,
            'title' => null,
            'visible' => true,
            'width' => null,
            'add_if' => null,
            'join_type' => 'leftJoin',
            'type_of_field' => null,
        ));

        $resolver->setAllowedTypes('cell_type', array('null', 'string'));
        $resolver->setAllowedTypes('class_name', array('null', 'string'));
        $resolver->setAllowedTypes('content_padding', array('null', 'string'));
        $resolver->setAllowedTypes('data', array('null', 'string'));
        $resolver->setAllowedTypes('default_content', array('null', 'string'));
        $resolver->setAllowedTypes('name', array('null', 'string'));
        $resolver->setAllowedTypes('orderable', 'bool');
        $resolver->setAllowedTypes('order_data', array('null', 'array', 'int'));
        $resolver->setAllowedTypes('order_sequence', array('null', 'array'));
        $resolver->setAllowedTypes('searchable', 'bool');
        $resolver->setAllowedTypes('title', array('null', 'string'));
        $resolver->setAllowedTypes('visible', 'bool');
        $resolver->setAllowedTypes('width', array('null', 'string'));
        $resolver->setAllowedTypes('add_if', array('null', 'Closure'));
        $resolver->setAllowedTypes('join_type', 'string');
        $resolver->setAllowedTypes('type_of_field', array('null', 'string'));

        $resolver->setAllowedValues('cell_type', array(null, 'th', 'td'));
        $resolver->setAllowedValues('join_type', array(null, 'join', 'leftJoin', 'innerJoin'));
        // @todo: $resolver->setAllowedValues('type_of_field', array(null, 'string', 'integer', 'boolean'));

        return $this;
    }

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function dqlConstraint($dql)
    {
        return preg_match('/^[a-zA-Z0-9_\\-\\.]+$/', $dql) ? true : false;
    }

    /**
     * {@inheritdoc}
     */
    public function isUnique()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function isAssociation()
    {
        return (false === strstr($this->dql, '.') ? false : true);
    }

    /**
     * {@inheritdoc}
     */
    public function isSelectColumn()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function addDataToOutputArray(array &$row)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function renderCellContent(array &$row)
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function renderPostCreateDatatableJsContent()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function allowedPositions()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnType()
    {
        return 'data';
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get cellType.
     *
     * @return null|string
     */
    public function getCellType()
    {
        return $this->cellType;
    }

    /**
     * Set cellType.
     *
     * @param null|string $cellType
     *
     * @return $this
     */
    public function setCellType($cellType)
    {
        $this->cellType = $cellType;

        return $this;
    }

    /**
     * Get className.
     *
     * @return null|string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * Set className.
     *
     * @param null|string $className
     *
     * @return $this
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * Get contentPadding.
     *
     * @return null|string
     */
    public function getContentPadding()
    {
        return $this->contentPadding;
    }

    /**
     * Set contentPadding.
     *
     * @param null|string $contentPadding
     *
     * @return $this
     */
    public function setContentPadding($contentPadding)
    {
        $this->contentPadding = $contentPadding;

        return $this;
    }

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
     * Set data.
     *
     * @param null|string $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get defaultContent.
     *
     * @return null|string
     */
    public function getDefaultContent()
    {
        return $this->defaultContent;
    }

    /**
     * Set defaultContent.
     *
     * @param null|string $defaultContent
     *
     * @return $this
     */
    public function setDefaultContent($defaultContent)
    {
        $this->defaultContent = $defaultContent;

        return $this;
    }

    /**
     * Get name.
     *
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set name.
     *
     * @param null|string $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get orderable.
     *
     * @return bool
     */
    public function getOrderable()
    {
        return $this->orderable;
    }

    /**
     * Set orderable.
     *
     * @param bool $orderable
     *
     * @return $this
     */
    public function setOrderable($orderable)
    {
        $this->orderable = $orderable;

        return $this;
    }

    /**
     * Get orderData.
     *
     * @return null|array|int
     */
    public function getOrderData()
    {
        if (is_array($this->orderData)) {
            return $this->optionToJson($this->orderData);
        }

        return $this->orderData;
    }

    /**
     * Set orderData.
     *
     * @param null|array|int $orderData
     *
     * @return $this
     */
    public function setOrderData($orderData)
    {
        $this->orderData = $orderData;

        return $this;
    }

    /**
     * Get orderSequence.
     *
     * @return null|array
     */
    public function getOrderSequence()
    {
        if (is_array($this->orderSequence)) {
            return $this->optionToJson($this->orderSequence);
        }

        return $this->orderSequence;
    }

    /**
     * Set orderSequence.
     *
     * @param null|array $orderSequence
     *
     * @return $this
     */
    public function setOrderSequence($orderSequence)
    {
        if (is_array($orderSequence)) {
            $this->checkOptions($orderSequence, array('asc', 'desc'));
        }

        $this->orderSequence = $orderSequence;

        return $this;
    }

    /**
     * Get searchable.
     *
     * @return bool
     */
    public function getSearchable()
    {
        return $this->searchable;
    }

    /**
     * Set searchable.
     *
     * @param bool $searchable
     *
     * @return $this
     */
    public function setSearchable($searchable)
    {
        $this->searchable = $searchable;

        return $this;
    }

    /**
     * Get title.
     *
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title.
     *
     * @param null|string $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get visible.
     *
     * @return bool
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
     * Set visible.
     *
     * @param bool $visible
     *
     * @return $this
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get width.
     *
     * @return null|string
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set width.
     *
     * @param null|string $width
     *
     * @return $this
     */
    public function setWidth($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * Get join type.
     *
     * @return string
     */
    public function getJoinType()
    {
        return $this->joinType;
    }

    /**
     * Set join type.
     *
     * @param string $joinType
     *
     * @return $this
     */
    public function setJoinType($joinType)
    {
        $this->joinType = $joinType;

        return $this;
    }

    /**
     * Get type of field.
     *
     * @return null|string
     */
    public function getTypeOfField()
    {
        return $this->typeOfField;
    }

    /**
     * Set type of field.
     *
     * @param null|string $typeOfField
     *
     * @return $this
     */
    public function setTypeOfField($typeOfField)
    {
        $this->typeOfField = $typeOfField;

        return $this;
    }

    /**
     * Set Filter instance.
     *
     * @param array $filterClassAndOptions
     *
     * @return $this
     * @throws Exception
     */
    public function setFilter(array $filterClassAndOptions)
    {
        if (count($filterClassAndOptions) != 2) {
            throw new Exception('AbstractColumn::setFilter(): Two arguments expected.');
        }

        if (!isset($filterClassAndOptions[0]) || !is_string($filterClassAndOptions[0]) && !$filterClassAndOptions[0] instanceof FilterInterface) {
            throw new Exception('AbstractColumn::setFilter(): Set a Filter class.');
        }

        if (!isset($filterClassAndOptions[1]) || !is_array($filterClassAndOptions[1])) {
            throw new Exception('AbstractColumn::setFilter(): Set an options array.');
        }

        /** @var \Sg\DatatablesBundle\Datatable\Filter\AbstractFilter $newFilter */
        $newFilter = FilterFactory::createFilter($filterClassAndOptions[0]);
        $this->filter = $newFilter->set($filterClassAndOptions[1]);

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
     * Get dql.
     *
     * @return null|string
     */
    public function getDql()
    {
        return $this->dql;
    }

    /**
     * Set dql.
     *
     * @param null|string $dql
     *
     * @return $this
     * @throws Exception
     */
    public function setDql($dql)
    {
        if (true === $this->dqlConstraint($dql)) {
            $this->dql = $dql;
        } else {
            throw new Exception("AbstractColumn::setDql(): $dql is not a valid for this Column.");
        }

        return $this;
    }

    /**
     * Get Twig.
     *
     * @return Twig_Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * Set Twig.
     *
     * @param Twig_Environment $twig
     *
     * @return $this
     */
    public function setTwig(Twig_Environment $twig)
    {
        $this->twig = $twig;

        return $this;
    }

    /**
     * Get index.
     *
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * Set index.
     *
     * @param int $index
     *
     * @return $this
     */
    public function setIndex($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Get datatableName.
     *
     * @return string
     */
    public function getDatatableName()
    {
        return $this->datatableName;
    }

    /**
     * Set datatableName.
     *
     * @param string $datatableName
     *
     * @return $this
     */
    public function setDatatableName($datatableName)
    {
        $this->datatableName = $datatableName;

        return $this;
    }

    /**
     * Get entityClassName.
     *
     * @return string
     */
    public function getEntityClassName()
    {
        return $this->entityClassName;
    }

    /**
     * Set entityClassName.
     *
     * @param string $entityClassName
     *
     * @return $this
     */
    public function setEntityClassName($entityClassName)
    {
        $this->entityClassName = $entityClassName;

        return $this;
    }
}
