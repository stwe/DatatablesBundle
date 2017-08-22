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
     * Unique Columns.
     *
     * @var array
     */
    private $uniqueColumns;

    /**
     * The fully-qualified class name of the entity (e.g. AppBundle\Entity\Post).
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
        $this->uniqueColumns = array();
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
        $column = Factory::create($class, ColumnInterface::class);
        $column->initOptions();

        $this->handleDqlProperties($dql, $options, $column);
        $this->setEnvironmentProperties($column);
        $column->set($options);

        $this->setTypeProperties($dql, $column);
        $this->addColumn($dql, $column);

        $this->checkUnique();

        return $this;
    }

    /**
     * Remove Column.
     *
     * @param null|string $dql
     *
     * @return $this
     */
    public function remove($dql)
    {
        foreach ($this->columns as $column) {
            if ($column->getDql() == $dql) {
                $this->removeColumn($column);
                break;
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
     * Get a unique Column by his type.
     *
     * @param string $columnType
     *
     * @return mixed|null
     */
    public function getUniqueColumn($columnType)
    {
        return array_key_exists($columnType, $this->uniqueColumns) ? $this->uniqueColumns[$columnType] : null;
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

        $column->setOriginalTypeOfField($metadata->getTypeOfField($field));

        return $this;
    }

    /**
     * Handle dql properties.
     *
     * @param string         $dql
     * @param array          $options
     * @param AbstractColumn $column
     *
     * @return $this
     */
    private function handleDqlProperties($dql, array $options, AbstractColumn $column)
    {
        // the Column 'data' property has normally the same value as 'dql'
        $column->setData($dql);

        if (!isset($options['dql'])) {
            $column->setCustomDql(false);
            $column->setDql($dql);
        } else {
            $column->setCustomDql(true);
        }

        return $this;
    }

    /**
     * Set environment properties.
     *
     * @param AbstractColumn $column
     *
     * @return $this
     */
    private function setEnvironmentProperties(AbstractColumn $column)
    {
        $column->setDatatableName($this->datatableName);
        $column->setEntityClassName($this->entityClassName);
        $column->setTwig($this->twig);

        return $this;
    }

    /**
     * Sets some types.
     *
     * @param string         $dql
     * @param AbstractColumn $column
     *
     * @return $this
     */
    private function setTypeProperties($dql, AbstractColumn $column)
    {
        if (true === $column->isSelectColumn() && false === $column->isCustomDql()) {
            $metadata = $this->metadata;
            $parts = explode('.', $dql);
            // add associations types
            if (true === $column->isAssociation()) {
                while (count($parts) > 1) {
                    $currentPart = array_shift($parts);

                    /** @noinspection PhpUndefinedMethodInspection */
                    $column->addTypeOfAssociation($metadata->getAssociationMapping($currentPart)['type']);
                    $metadata = $this->getMetadataFromAssociation($currentPart, $metadata);
                }
            } else {
                $column->setTypeOfAssociation(null);
            }

            // set the type of the field
            $this->setTypeOfField($metadata, $column, $parts[0]);
        } else {
            $column->setTypeOfAssociation(null);
            $column->setOriginalTypeOfField(null);
        }

        return $this;
    }

    /**
     * Adds a Column.
     *
     * @param string         $dql
     * @param AbstractColumn $column
     *
     * @return $this
     */
    private function addColumn($dql, AbstractColumn $column)
    {
        if (true === $column->callAddIfClosure()) {
            $this->columns[] = $column;
            $index = count($this->columns) - 1;
            $this->columnNames[$dql] = $index;
            $column->setIndex($index);

            // Use the Column-Index as data source for Columns with 'dql' === null
            if (null === $column->getDql() && null === $column->getData()) {
                $column->setData($index);
            }

            if (true === $column->isUnique()) {
                $this->uniqueColumns[$column->getColumnType()] = $column;
            }
        }

        return $this;
    }

    /**
     * Removes a Column.
     *
     * @param AbstractColumn $column
     *
     * @return $this
     */
    private function removeColumn(AbstractColumn $column)
    {
        foreach ($this->columns as $k => $c) {
            if ($c == $column) {
                unset($this->columns[$k]);
                $this->columns = array_values($this->columns);
                break;
            }
        }

        foreach ($this->uniqueColumns as $k => $c) {
            if ($c == $column) {
                unset($this->uniqueColumns[$k]);
                $this->uniqueColumns = array_values($this->uniqueColumns);
                break;
            }
        }

        return $this;
    }

    /**
     * Check unique.
     *
     * @return $this
     * @throws Exception
     */
    private function checkUnique()
    {
        $unique = $this->uniqueColumns;

        if (count(array_unique($unique)) < count($unique)) {
            throw new Exception('ColumnBuilder::checkUnique(): Unique columns are only allowed once.');
        }

        return $this;
    }
}
