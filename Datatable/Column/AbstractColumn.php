<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Doctrine\DBAL\Types\Type as DoctrineType;
use Doctrine\ORM\Mapping\ClassMetadataInfo;
use Exception;
use Sg\DatatablesBundle\Datatable\AddIfTrait;
use Sg\DatatablesBundle\Datatable\Editable\EditableInterface;
use Sg\DatatablesBundle\Datatable\OptionsTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

abstract class AbstractColumn implements ColumnInterface
{
    use OptionsTrait;

    // Use an 'add_if' option to check in ColumnBuilder if the Column can be added.
    use AddIfTrait;

    //-------------------------------------------------
    // Column Types
    //-------------------------------------------------

    /**
     * Identifies a Data Column.
     */
    const DATA_COLUMN = 'data';

    /**
     * Identifies an Action Column.
     */
    const ACTION_COLUMN = 'action';

    /**
     * Identifies a Multiselect Column.
     */
    const MULTISELECT_COLUMN = 'multiselect';

    /**
     * Identifies a Virtual Column.
     */
    const VIRTUAL_COLUMN = 'virtual';

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
     * Default: null.
     *
     * @var string|null
     */
    protected $cellType;

    /**
     * Adds a class to each cell in a column.
     * Default: null.
     *
     * @var string|null
     */
    protected $className;

    /**
     * Add padding to the text content used when calculating the optimal with for a table.
     * Default: null.
     *
     * @var string|null
     */
    protected $contentPadding;

    /**
     * Set the data source for the column from the rows data object / array.
     * DataTables default: Takes the index value of the column automatically.
     *
     * This property has normally the same value as $this->dql.
     * Is set in the ColumnBuilder.
     *
     * @var string|null
     */
    protected $data;

    /**
     * Set default, static, content for a column.
     * Show an information message for a field that can have a 'null' or 'undefined' value.
     * Default: null.
     *
     * @var string|null
     */
    protected $defaultContent;

    /**
     * Set a descriptive name for a column. Only needed when working with DataTables' API.
     * Default: null.
     *
     * @var string|null
     */
    protected $name;

    /**
     * Enable or disable ordering on this column.
     * DataTables default: true
     * Default: true.
     *
     * @var bool
     */
    protected $orderable;

    /**
     * Define multiple column ordering as the default order for a column.
     * DataTables default: Takes the index value of the column automatically.
     * Default: null.
     *
     * @var array|int|null
     */
    protected $orderData;

    /**
     * Order direction application sequence.
     * DataTables default: ['asc', 'desc']
     * Default: null.
     *
     * @var array|null
     */
    protected $orderSequence;

    /**
     * Enable or disable filtering on the data in this column.
     * DataTables default: true
     * Default: true.
     *
     * @var bool
     */
    protected $searchable;

    /**
     * Set the column title.
     * DataTables default: Value read from the column's header cell.
     * Default: null.
     *
     * @var string|null
     */
    protected $title;

    /**
     * Enable or disable the display of this column.
     * DataTables default: true
     * Default: true.
     *
     * @var bool
     */
    protected $visible;

    /**
     * Column width assignment.
     * DataTables default: Auto-detected from the table's content.
     * Default: null.
     *
     * @var string|null
     */
    protected $width;

    //-------------------------------------------------
    // Custom Options
    //-------------------------------------------------

    /**
     * Join type (default: 'leftJoin'), if the column represents an association.
     * Default: 'leftJoin'.
     *
     * @var string
     */
    protected $joinType;

    /**
     * The data type of the column.
     * Is set automatically in ColumnBuilder when 'null'.
     * Default: null.
     *
     * @var string|null
     */
    protected $typeOfField;

    /**
     * The first argument of ColumnBuilders 'add' function.
     * The DatatableQuery class works with this property.
     * If $dql is used as an option, the ColumnBuilder sets $customDql to true.
     *
     * @var string|null
     */
    protected $dql;

    //-------------------------------------------------
    // Extensions Options
    //-------------------------------------------------

    /**
     * Set column's visibility priority.
     * Requires the Responsive extension.
     * Default: null.
     *
     * @var int|null
     */
    protected $responsivePriority;

    //-------------------------------------------------
    // Other Properties
    //-------------------------------------------------

    /**
     * True if DQL option is provided.
     * Is set in the ColumnBuilder.
     *
     * @var bool
     */
    protected $customDql;

    /**
     * The Twig Environment to render Twig templates in Column rowes.
     * Is set in the ColumnBuilder.
     *
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * The Router.
     * Is set in the ColumnBuilder.
     *
     * @var RouterInterface
     */
    protected $router;

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
     * The fully-qualified class name of the entity (e.g. AppBundle\Entity\Post).
     * Is set in the ColumnBuilder.
     *
     * @var string
     */
    protected $entityClassName;

    /**
     * The type of association.
     * Is set in the ColumnBuilder.
     *
     * @var array|null
     */
    protected $typeOfAssociation;

    /**
     * Saves the original type of field for the DatatableController editAction.
     * Is set in the ColumnBuilder.
     *
     * @var string|null
     */
    protected $originalTypeOfField;

    /**
     * If the field is sent in the response, to show in the webpage
     * Is set in the ColumnBuilder.
     * Default: true.
     *
     * @var bool
     */
    protected $sentInResponse;
    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        // 'dql' and 'data' options need no default value
        $resolver->setDefined(['dql', 'data']);

        $resolver->setDefaults([
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
            'responsive_priority' => null,
            'sent_in_response' => true,
        ]);

        $resolver->setAllowedTypes('cell_type', ['null', 'string']);
        $resolver->setAllowedTypes('class_name', ['null', 'string']);
        $resolver->setAllowedTypes('content_padding', ['null', 'string']);
        $resolver->setAllowedTypes('dql', ['null', 'string']);
        $resolver->setAllowedTypes('data', ['null', 'string']);
        $resolver->setAllowedTypes('default_content', ['null', 'string']);
        $resolver->setAllowedTypes('name', ['null', 'string']);
        $resolver->setAllowedTypes('orderable', 'bool');
        $resolver->setAllowedTypes('order_data', ['null', 'array', 'int']);
        $resolver->setAllowedTypes('order_sequence', ['null', 'array']);
        $resolver->setAllowedTypes('searchable', 'bool');
        $resolver->setAllowedTypes('title', ['null', 'string']);
        $resolver->setAllowedTypes('visible', 'bool');
        $resolver->setAllowedTypes('width', ['null', 'string']);
        $resolver->setAllowedTypes('add_if', ['null', 'Closure']);
        $resolver->setAllowedTypes('join_type', 'string');
        $resolver->setAllowedTypes('type_of_field', ['null', 'string']);
        $resolver->setAllowedTypes('responsive_priority', ['null', 'int']);
        $resolver->setAllowedTypes('sent_in_response', ['bool']);

        $resolver->setAllowedValues('cell_type', [null, 'th', 'td']);
        $resolver->setAllowedValues('join_type', [null, 'join', 'leftJoin', 'innerJoin']);
        $resolver->setAllowedValues('type_of_field', array_merge([null], array_keys(DoctrineType::getTypesMap())));

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
        if (true === $this->isCustomDql()) {
            return true;
        }

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
        return false === strstr($this->dql, '.') ? false : true;
    }

    /**
     * {@inheritdoc}
     */
    public function isToManyAssociation()
    {
        if (true === $this->isAssociation() && null !== $this->typeOfAssociation) {
            if (\in_array(ClassMetadataInfo::ONE_TO_MANY, $this->typeOfAssociation, true) || \in_array(ClassMetadataInfo::MANY_TO_MANY, $this->typeOfAssociation, true)) {
                return true;
            }

            return false;
        }

        return false;
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
    public function getOptionsTemplate()
    {
        return '@SgDatatables/column/column.html.twig';
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
        $this->isToManyAssociation() ? $this->renderToMany($row) : $this->renderSingleField($row);
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
        return self::DATA_COLUMN;
    }

    /**
     * {@inheritdoc}
     */
    public function isEditableContentRequired(array $row)
    {
        if (isset($this->editable)) {
            if ($this->editable instanceof EditableInterface && true === $this->editable->callEditableIfClosure($row)) {
                return true;
            }
        }

        return false;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return string|null
     */
    public function getCellType()
    {
        return $this->cellType;
    }

    /**
     * @param string|null $cellType
     *
     * @return $this
     */
    public function setCellType($cellType)
    {
        $this->cellType = $cellType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string|null $className
     *
     * @return $this
     */
    public function setClassName($className)
    {
        $this->className = $className;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getContentPadding()
    {
        return $this->contentPadding;
    }

    /**
     * @param string|null $contentPadding
     *
     * @return $this
     */
    public function setContentPadding($contentPadding)
    {
        $this->contentPadding = $contentPadding;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * @param string|null $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDefaultContent()
    {
        return $this->defaultContent;
    }

    /**
     * @param string|null $defaultContent
     *
     * @return $this
     */
    public function setDefaultContent($defaultContent)
    {
        $this->defaultContent = $defaultContent;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     *
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function getOrderable()
    {
        return $this->orderable;
    }

    /**
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
     * @return array|int|null
     */
    public function getOrderData()
    {
        if (\is_array($this->orderData)) {
            return $this->optionToJson($this->orderData);
        }

        return $this->orderData;
    }

    /**
     * @param array|int|null $orderData
     *
     * @return $this
     */
    public function setOrderData($orderData)
    {
        $this->orderData = $orderData;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getOrderSequence()
    {
        if (\is_array($this->orderSequence)) {
            return $this->optionToJson($this->orderSequence);
        }

        return $this->orderSequence;
    }

    /**
     * @param array|null $orderSequence
     *
     * @return $this
     */
    public function setOrderSequence($orderSequence)
    {
        $this->orderSequence = $orderSequence;

        return $this;
    }

    /**
     * @return bool
     */
    public function getSearchable()
    {
        return $this->searchable;
    }

    /**
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
     * @return string|null
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     *
     * @return $this
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return bool
     */
    public function getVisible()
    {
        return $this->visible;
    }

    /**
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
     * @return string|null
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @param string|null $width
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
     * @return string|null
     */
    public function getTypeOfField()
    {
        return $this->typeOfField;
    }

    /**
     * Set type of field.
     *
     * @param string|null $typeOfField
     *
     * @return $this
     */
    public function setTypeOfField($typeOfField)
    {
        $this->typeOfField = $typeOfField;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getResponsivePriority()
    {
        return $this->responsivePriority;
    }

    /**
     * @param int|null $responsivePriority
     *
     * @return $this
     */
    public function setResponsivePriority($responsivePriority)
    {
        $this->responsivePriority = $responsivePriority;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDql()
    {
        return $this->dql;
    }

    /**
     * @param string|null $dql
     *
     * @throws Exception
     *
     * @return $this
     */
    public function setDql($dql)
    {
        if (true === $this->dqlConstraint($dql)) {
            $this->dql = $dql;
        } else {
            throw new Exception("AbstractColumn::setDql(): {$dql} is not valid for this Column.");
        }

        return $this;
    }

    /**
     * @return bool
     */
    public function isCustomDql()
    {
        return $this->customDql;
    }

    /**
     * @param bool $customDql
     *
     * @return $this
     */
    public function setCustomDql($customDql)
    {
        $this->customDql = $customDql;

        return $this;
    }

    /**
     * @return Twig_Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * @return $this
     */
    public function setTwig(Environment $twig)
    {
        $this->twig = $twig;

        return $this;
    }

    /**
     * @return RouterInterface
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return $this
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;

        return $this;
    }

    /**
     * @return int
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
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
     * @return string
     */
    public function getDatatableName()
    {
        return $this->datatableName;
    }

    /**
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
     * @return string
     */
    public function getEntityClassName()
    {
        return $this->entityClassName;
    }

    /**
     * @param string $entityClassName
     *
     * @return $this
     */
    public function setEntityClassName($entityClassName)
    {
        $this->entityClassName = $entityClassName;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getTypeOfAssociation()
    {
        return $this->typeOfAssociation;
    }

    /**
     * @param array|null $typeOfAssociation
     *
     * @return $this
     */
    public function setTypeOfAssociation($typeOfAssociation)
    {
        $this->typeOfAssociation = $typeOfAssociation;

        return $this;
    }

    /**
     * Add a typeOfAssociation.
     *
     * @param int $typeOfAssociation
     *
     * @return $this
     */
    public function addTypeOfAssociation($typeOfAssociation)
    {
        $this->typeOfAssociation[] = $typeOfAssociation;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getOriginalTypeOfField()
    {
        return $this->originalTypeOfField;
    }

    /**
     * @param string|null $originalTypeOfField
     *
     * @return $this
     */
    public function setOriginalTypeOfField($originalTypeOfField)
    {
        $this->originalTypeOfField = $originalTypeOfField;

        return $this;
    }

    /**
     * @return bool
     */
    public function getSentInResponse()
    {
        return $this->sentInResponse;
    }

    /**
     * @param bool $sentInResponse
     *
     * @return $this
     */
    public function setSentInResponse($sentInResponse)
    {
        $this->sentInResponse = $sentInResponse;

        return $this;
    }
}
