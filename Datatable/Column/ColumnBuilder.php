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

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\Persistence\Mapping\ClassMetadata;
use Exception;
use Sg\DatatablesBundle\Datatable\Factory;
use Symfony\Component\Routing\RouterInterface;
use Twig\Environment;

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
     * The router.
     *
     * @var RouterInterface
     */
    private $router;

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

    /**
     * @param string $datatableName
     */
    public function __construct(ClassMetadata $metadata, Environment $twig, RouterInterface $router, $datatableName, EntityManagerInterface $em)
    {
        $this->metadata = $metadata;
        $this->twig = $twig;
        $this->router = $router;
        $this->datatableName = $datatableName;
        $this->em = $em;

        $this->columns = [];
        $this->columnNames = [];
        $this->uniqueColumns = [];
        $this->entityClassName = $metadata->getName();
    }

    //-------------------------------------------------
    // Builder
    //-------------------------------------------------

    /**
     * Add Column.
     *
     * @param string|null            $dql
     * @param ColumnInterface|string $class
     *
     * @throws Exception
     *
     * @return $this
     */
    public function add($dql, $class, array $options = [])
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
     * @param string|null $dql
     *
     * @return $this
     */
    public function remove($dql)
    {
        foreach ($this->columns as $column) {
            if ($column->getDql() === $dql) {
                $this->removeColumn($dql, $column);

                break;
            }
        }

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
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
        return \array_key_exists($columnType, $this->uniqueColumns) ? $this->uniqueColumns[$columnType] : null;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * @param string $entityName
     *
     * @throws Exception
     *
     * @return ClassMetadata
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
     * @param string $association
     *
     * @return ClassMetadata
     */
    private function getMetadataFromAssociation($association, ClassMetadata $metadata)
    {
        $targetClass = $metadata->getAssociationTargetClass($association);

        return $this->getMetadata($targetClass);
    }

    /**
     * @param string $field
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
     * @param string $dql
     *
     * @return $this
     */
    private function handleDqlProperties($dql, array $options, AbstractColumn $column)
    {
        // the Column 'data' property has normally the same value as 'dql'
        $column->setData($dql);

        if (! isset($options['dql'])) {
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
     * @return $this
     */
    private function setEnvironmentProperties(AbstractColumn $column)
    {
        $column->setDatatableName($this->datatableName);
        $column->setEntityClassName($this->entityClassName);
        $column->setTwig($this->twig);
        $column->setRouter($this->router);

        return $this;
    }

    /**
     * Sets some types.
     *
     * @param string $dql
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
                while (\count($parts) > 1) {
                    $currentPart = array_shift($parts);

                    // @noinspection PhpUndefinedMethodInspection
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
     * @param string $dql
     *
     * @return $this
     */
    private function addColumn($dql, AbstractColumn $column)
    {
        if (true === $column->callAddIfClosure()) {
            $this->columns[] = $column;
            $index = \count($this->columns) - 1;
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
     * @param string $dql
     *
     * @return $this
     */
    private function removeColumn($dql, AbstractColumn $column)
    {
        // Remove column from columns
        foreach ($this->columns as $k => $c) {
            if ($c === $column) {
                unset($this->columns[$k]);
                $this->columns = array_values($this->columns);

                break;
            }
        }

        // Remove column from columnNames
        if (\array_key_exists($dql, $this->columnNames)) {
            unset($this->columnNames[$dql]);
        }

        // Reindex columnNames
        foreach ($this->columns as $k => $c) {
            $this->columnNames[$c->getDql()] = $k;
        }

        // Remove column from uniqueColumns
        foreach ($this->uniqueColumns as $k => $c) {
            if ($c === $column) {
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
     * @throws Exception
     *
     * @return $this
     */
    private function checkUnique(): self
    {
        $unique = $this->uniqueColumns;

        if (\count(array_unique($unique)) < \count($unique)) {
            throw new Exception('ColumnBuilder::checkUnique(): Unique columns are only allowed once.');
        }

        return $this;
    }
}
