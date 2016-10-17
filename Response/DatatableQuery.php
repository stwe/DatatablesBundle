<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Response;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Exception;

/**
 * Class DatatableQuery
 *
 * @package Sg\DatatablesBundle\Response
 */
class DatatableQuery
{
    /**
     * @var array
     */
    private $requestParams;

    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var string
     */
    private $entityName;

    /**
     * @var ClassMetadata
     */
    private $metadata;

    /**
     * @var string
     */
    private $tableName;

    /**
     * @var mixed
     */
    private $rootEntityIdentifier;

    /**
     * @var QueryBuilder
     */
    private $qb;

    /**
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * @var array
     */
    private $columns;

    /**
     * @var array
     */
    private $selectColumns;

    /**
     * @var array
     */
    private $searchColumns;

    /**
     * @var array
     */
    private $orderColumns;

    /**
     * @var array
     */
    private $joins;

    /**
     * @var Paginator
     */
    private $paginator;

    //-------------------------------------------------
    // Ctor. && Init column arrays
    //-------------------------------------------------

    /**
     * DatatableQuery constructor.
     *
     * @param array                  $requestParams
     * @param EntityManagerInterface $em
     */
    public function __construct(
        array $requestParams,
        EntityManagerInterface $em
    )
    {
        $this->requestParams = $requestParams;
        $this->em = $em;

        $this->entityName = $this->requestParams['sg_datatable_request_data_entity'];

        $this->metadata = $this->getMetadata($this->entityName);
        $this->tableName = $this->getTableName($this->metadata);
        $this->rootEntityIdentifier = $this->getIdentifier($this->metadata);

        $this->qb = $this->em->createQueryBuilder();
        $this->accessor = PropertyAccess::createPropertyAccessor();

        $this->columns = $this->requestParams['sg_datatable_request_data_columns'];
        $this->selectColumns = array();
        $this->searchColumns = array();
        $this->orderColumns = array();
        $this->joins = array();

        $this->paginator = null;

        $this->initColumnArrays();
    }

    /**
     * Init column arrays for select, order, search.
     *
     * @return $this
     */
    private function initColumnArrays()
    {
        $columns = json_decode($this->columns);

        foreach ($columns as $key => $column) {
            $data = $this->accessor->getValue($column, 'dql');

            $currentPart = $this->tableName;
            $currentAlias = $currentPart;
            $metadata = $this->metadata;

            if (true === $this->accessor->getValue($column, 'selectColumn')) {
                $parts = explode('.', $data);

                while (count($parts) > 1) {
                    $previousPart = $currentPart;
                    $previousAlias = $currentAlias;

                    $currentPart = array_shift($parts);
                    $currentAlias = ($previousPart === $this->tableName ? '' : $previousPart . '_') . $currentPart;

                    if (!array_key_exists($previousAlias . '.' . $currentPart, $this->joins)) {
                        $this->addJoin($previousAlias . '.' . $currentPart, $currentAlias, $this->accessor->getValue($column, 'joinType'));
                    }

                    $metadata = $this->setIdentifierFromAssociation($currentAlias, $currentPart, $metadata);
                }

                $this->addSelectColumn($currentAlias, $this->getIdentifier($metadata));
                $this->addSelectColumn($currentAlias, $parts[0]);
                $this->addSearchOrderColumn($column, $currentAlias, $parts[0]);
            } else {
                $this->orderColumns[] = null;
                $this->searchColumns[] = null;
            }
        }

        return $this;
    }

    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * Build query.
     *
     * @return $this
     */
    public function buildQuery()
    {
        $this->setSelectFrom();
        $this->setJoins($this->qb);

        /*
        $this->setWhere($this->qb);
        $this->setWhereAllCallback($this->qb);
        $this->setOrderBy();
        $this->setLimit();
        */

        return $this;
    }

    /**
     * Get qb.
     *
     * @return QueryBuilder
     */
    public function getQb()
    {
        return $this->qb;
    }

    /**
     * Set qb.
     *
     * @param QueryBuilder $qb
     *
     * @return $this
     */
    public function setQb($qb)
    {
        $this->qb = $qb;

        return $this;
    }

    /**
     * Get response.
     *
     * @param bool $outputWalkers
     *
     * @return JsonResponse
     */
    public function getResponse($outputWalkers = false)
    {
        $this->paginator = new Paginator($this->execute(), true);
        $this->paginator->setUseOutputWalkers($outputWalkers);

        /*
        $formatter = new DatatableFormatter($this);
        $formatter->runFormatter();

        $countAllResults = $this->datatableView->getOptions()->getCountAllResults();
        */

        $outputHeader = array(
            'draw' => (int) $this->requestParams['draw'],
            'recordsTotal' => 100,
            'recordsFiltered' => 100
        );

        $output = array();
        foreach ($this->paginator as $row) {
            $output['data'][] = $row;
        }

        return new JsonResponse(array_merge($outputHeader, $output));
    }

    //-------------------------------------------------
    // Private - Setup query
    //-------------------------------------------------

    /**
     * Set select from.
     *
     * @return $this
     */
    private function setSelectFrom()
    {
        foreach ($this->selectColumns as $key => $value) {
            $this->qb->addSelect('partial ' . $key . '.{' . implode(',', $this->selectColumns[$key]) . '}');
        }

        $this->qb->from($this->entityName, $this->tableName);

        return $this;
    }

    /**
     * Set joins.
     *
     * @param QueryBuilder $qb
     *
     * @return $this
     */
    private function setJoins(QueryBuilder $qb)
    {
        foreach ($this->joins as $key => $value) {
            $qb->$value['type']($key, $value['alias']);
        }

        return $this;
    }

    /**
     * Constructs a Query instance.
     *
     * @return Query
     */
    private function execute()
    {
        $query = $this->qb->getQuery();
        $query->setHydrationMode(Query::HYDRATE_ARRAY);

        return $query;
    }

    //-------------------------------------------------
    // Private - Helper
    //-------------------------------------------------

    /**
     * Set identifier from association.
     *
     * @author Gaultier Boniface <https://github.com/wysow>
     *
     * @param string|array       $association
     * @param string             $key
     * @param ClassMetadata|null $metadata
     *
     * @return ClassMetadata
     * @throws Exception
     */
    private function setIdentifierFromAssociation($association, $key, $metadata = null)
    {
        if (null === $metadata) {
            $metadata = $this->metadata;
        }

        $targetEntityClass = $metadata->getAssociationTargetClass($key);
        $targetMetadata = $this->getMetadata($targetEntityClass);
        $this->addSelectColumn($association, $this->getIdentifier($targetMetadata));

        return $targetMetadata;
    }

    /**
     * Add select column.
     *
     * @param string $columnTableName
     * @param string $data
     *
     * @return $this
     */
    private function addSelectColumn($columnTableName, $data)
    {
        if (isset($this->selectColumns[$columnTableName])) {
            if (!in_array($data, $this->selectColumns[$columnTableName])) {
                $this->selectColumns[$columnTableName][] = $data;
            }
        } else {
            $this->selectColumns[$columnTableName][] = $data;
        }

        return $this;
    }

    /**
     * Add search/order column.
     *
     * @param object $column
     * @param string $columnTableName
     * @param string $data
     *
     * @return $this
     */
    private function addSearchOrderColumn($column, $columnTableName, $data)
    {
        true === $this->accessor->getValue($column, 'orderable') ? $this->orderColumns[] = $columnTableName . '.' . $data : $this->orderColumns[] = null;
        true === $this->accessor->getValue($column, 'searchable') ? $this->searchColumns[] = $columnTableName . '.' . $data : $this->searchColumns[] = null;

        return $this;
    }

    /**
     * Add join.
     *
     * @param string $columnTableName
     * @param string $alias
     * @param string $type
     *
     * @return $this
     */
    private function addJoin($columnTableName, $alias, $type)
    {
        $this->joins[$columnTableName] = array(
            'alias' => $alias,
            'type' => $type
        );

        return $this;
    }

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
            throw new Exception('DatatableQuery::getMetadata(): Given object ' . $entityName . ' is not a Doctrine Entity.');
        }

        return $metadata;
    }

    /**
     * Get table name.
     *
     * @param ClassMetadata $metadata
     *
     * @return string
     */
    private function getTableName(ClassMetadata $metadata)
    {
        return strtolower($metadata->getTableName());
    }

    /**
     * Get identifier.
     *
     * @param ClassMetadata $metadata
     *
     * @return mixed
     */
    private function getIdentifier(ClassMetadata $metadata)
    {
        $identifiers = $metadata->getIdentifierFieldNames();

        return array_shift($identifiers);
    }
}
