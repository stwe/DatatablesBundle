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

use Sg\DatatablesBundle\Datatable\Factory;

use Doctrine\ORM\Mapping\ClassMetadata;
use Twig_Environment;
use Exception;

/**
 * Class ColumnBuilder
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ColumnBuilder
{
    /**
     * The class metadata.
     *
     * @var ClassMetadata
     */
    private $metadata;

    /**
     * The Twig Environment.
     *
     * @var Twig_Environment
     */
    private $twig;

    /**
     * The generated Columns.
     *
     * @var array
     */
    private $columns;

    /**
     * This variable stores the array of column names as keys and column ids as values
     * in order to perform search column id by name.
     *
     * @var array
     */
    private $columnNames;

    /**
     * True when a unique Column was created.
     *
     * @var bool
     */
    private $uniqueFlag;

    /**
     * A generated MultiselectColumn.
     *
     * @var null|ColumnInterface
     */
    private $multiselectColumn;

    /**
     * The name of the associated Datatable.
     *
     * @var string
     */
    private $datatableName;

    /**
     * The fully-qualified class name of the entity.
     *
     * @var string
     */
    private $entityClassName;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * ColumnBuilder constructor.
     *
     * @param ClassMetadata    $metadata
     * @param Twig_Environment $twig
     * @param string           $datatableName
     */
    public function __construct(ClassMetadata $metadata, Twig_Environment $twig, $datatableName)
    {
        $this->metadata = $metadata;
        $this->twig = $twig;
        $this->columns = array();
        $this->columnNames = array();
        $this->uniqueFlag = false;
        $this->multiselectColumn = null;
        $this->datatableName = $datatableName;
        $this->entityClassName = $metadata->getName();
    }

    //-------------------------------------------------
    // Builder
    //-------------------------------------------------

    /**
     * Add Column.
     *
     * @param null|string            $dql
     * @param string|ColumnInterface $class
     * @param array                  $options
     *
     * @return $this
     * @throws Exception
     */
    public function add($dql, $class, array $options = array())
    {
        /**
         * @var AbstractColumn $column
         */
        $column = Factory::create($class, ColumnInterface::class);
        // creates the empty Column Options array and the Property Accessor
        $column->initOptions(false);
        // the Column 'data' property has normally the same value as 'dql'
        $column->setDql($dql);
        $column->setData($dql);
        // set the name of the Datatable
        $column->setDatatableName($this->datatableName);
        // set the table name
        $column->setEntityClassName($this->entityClassName);
        // inject twig for rendering special Column content
        $column->setTwig($this->twig);
        // resolve options - !!'data' can be modified again!!
        $column->set($options);

        if (null === $column->getTypeOfField() && true === $column->isSelectColumn()) {
            if (true === $column->isAssociation()) {
                // @todo: set type of field for association
                $column->setTypeOfField(null);
            } else {
                $column->setTypeOfField($this->metadata->getTypeOfField($dql));
            }
        }

        if (true === $column->callAddIfClosure()) {
            $this->columns[] = $column;
            $index = count($this->columns) - 1;
            $this->columnNames[$dql] = $index;
            $column->setIndex($index);

            // Use the Column-Index as data source for Columns with 'dql' === null
            if (null === $column->getDql() && null === $column->getData()) {
                $column->setData($index);
            }
        }

        // @todo: two or more unique column types
        if (true === $column->isUnique()) {
            if (false === $this->uniqueFlag) {
                $this->uniqueFlag = true;
                if ('multiselect' === $column->getColumnType()) {
                    $this->multiselectColumn = $column;
                }
            } else {
                throw new Exception('ColumnBuilder::add(): There is only one unique Column allowed. Check the Column with index: ' . $column->getIndex() . '.');
            }
        }

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get columns.
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get columnNames.
     *
     * @return array
     */
    public function getColumnNames()
    {
        return $this->columnNames;
    }

    /**
     * Get multiselectColumn.
     *
     * @return null|ColumnInterface
     */
    public function getMultiselectColumn()
    {
        return $this->multiselectColumn;
    }
}
