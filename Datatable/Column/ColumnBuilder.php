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

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\EntityManagerInterface;
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
     * The name of the associated Datatable.
     *
     * @var string
     */
    private $datatableName;

    /**
     * The doctrine orm entity manager service.
     *
     * @var EntityManagerInterface
     */
    private $em;

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
     * @param ClassMetadata          $metadata
     * @param Twig_Environment       $twig
     * @param string                 $datatableName
     * @param EntityManagerInterface $em
     */
    public function __construct(ClassMetadata $metadata, Twig_Environment $twig, $datatableName, EntityManagerInterface $em)
    {
        $this->metadata = $metadata;
        $this->twig = $twig;
        $this->datatableName = $datatableName;
        $this->em = $em;

        $this->columns = array();
        $this->columnNames = array();
        $this->uniqueFlag = false;
        $this->multiselectColumn = null;
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
        $column->setData($dql);
        if (!isset($options['dql'])) {
            $column->setDql($dql, true);
            $column->setCustomDql(false);
        } else {
            $column->setCustomDql(true);
        }
        // set the name of the Datatable
        $column->setDatatableName($this->datatableName);
        // set the table name
        $column->setEntityClassName($this->entityClassName);
        // inject twig for rendering special Column content
        $column->setTwig($this->twig);
        // resolve options - !!'data' can be modified again!!
        $column->set($options);

        if (true === $column->isSelectColumn()) {
            if (true === $column->isAssociation()) {
                $parts = explode('.', $dql);
                $metadata = $this->metadata;

                while (count($parts) > 1) {
                    $currentPart = array_shift($parts);

                    /** @noinspection PhpUndefinedMethodInspection */
                    $column->addTypeOfAssociation($metadata->getAssociationMapping($currentPart)['type']);
                    $metadata = $this->getMetadataFromAssociation($currentPart, $metadata);
                }

                $this->setTypeOfField($metadata, $column, $parts[0]);
            } else {
                $this->setTypeOfField($this->metadata, $column, $dql);
                $column->setTypeOfAssociation(null);
            }
        } else {
            $column->setTypeOfField(null);
            $column->setTypeOfAssociation(null);
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
                throw new Exception('ColumnBuilder::add(): There is only one unique Column allowed. Check the Column with index: '.$column->getIndex().'.');
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

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Get metadata.
     *
     * @param string $entityName
     *
     * @return ClassMetadata
     * @throws Exception
     */
    private function getMetadata($entityName)
    {
        try {
            $metadata = $this->em->getMetadataFactory()->getMetadataFor($entityName);
        } catch (MappingException $e) {
            throw new Exception('DatatableQueryBuilder::getMetadata(): Given object '.$entityName.' is not a Doctrine Entity.');
        }

        return $metadata;
    }

    /**
     * Get metadata from association.
     *
     * @param string        $association
     * @param ClassMetadata $metadata
     *
     * @return ClassMetadata
     */
    private function getMetadataFromAssociation($association, ClassMetadata $metadata)
    {
        $targetClass = $metadata->getAssociationTargetClass($association);
        $targetMetadata = $this->getMetadata($targetClass);

        return $targetMetadata;
    }

    /**
     * Set typeOfField.
     *
     * @param ClassMetadata  $metadata
     * @param AbstractColumn $column
     * @param string         $field
     *
     * @return $this
     */
    private function setTypeOfField(ClassMetadata $metadata, AbstractColumn $column, $field)
    {
        if (null === $column->getTypeOfField()) {
            $column->setTypeOfField($metadata->getTypeOfField($field));
        }

        return $this;
    }
}
