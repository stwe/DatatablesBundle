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

use Doctrine\Common\Collections\Criteria;
use Sg\DatatablesBundle\Datatable\Column\ColumnInterface;
use Sg\DatatablesBundle\Datatable\Filter\FilterInterface;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Sg\DatatablesBundle\Datatable\Options;
use Sg\DatatablesBundle\Datatable\Features;
use Sg\DatatablesBundle\Datatable\Ajax;

use Doctrine\Common\Persistence\Mapping\ClassMetadata;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Exception;

/**
 * Class DatatableQueryBuilder
 *
 * @todo: remove phpcs warnings
 *
 * @package Sg\DatatablesBundle\Response
 */
class DatatableQueryBuilder
{
    /**
     * @internal
     */
    const DISABLE_PAGINATION = -1;

    /**
     * @internal
     */
    const INIT_PARAMETER_COUNTER = 100;

    /**
     * $_GET or $_POST parameters.
     *
     * @var array
     */
    private $requestParams;

    /**
     * The EntityManager.
     *
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * The name of the entity.
     *
     * @var string
     */
    private $entityName;

    /**
     * The short name of the entity.
     *
     * @var string
     */
    private $entityShortName;

    /**
     * The class metadata for $entityName.
     *
     * @var ClassMetadata
     */
    private $metadata;

    /**
     * The root ID of the entity.
     *
     * @var mixed
     */
    private $rootEntityIdentifier;

    /**
     * The QueryBuilder.
     *
     * @var QueryBuilder
     */
    private $qb;

    /**
     * The PropertyAccessor.
     * Provides functions to read and write from/to an object or array using a simple string notation.
     *
     * @var PropertyAccessor
     */
    private $accessor;

    /**
     * All Columns of the Datatable.
     *
     * @var array
     */
    private $columns;

    /**
     * Contains all Columns to create a SELECT FROM statement.
     *
     * @var array
     */
    private $selectColumns;

    /**
     * Contains an information for each Column, whether to search in it.
     *
     * @var array
     */
    private $searchColumns;

    /**
     * Contains an information about each column, whether it is sortable.
     *
     * @var array
     */
    private $orderColumns;

    /**
     * Contains all informations to create joins.
     *
     * @var array
     */
    private $joins;

    /**
     * The Datatable Options instance.
     *
     * @var Options
     */
    private $options;

    /**
     * The Datatable Features instance.
     *
     * @var Features
     */
    private $features;

    /**
     * The Datatable Ajax instance.
     *
     * @var Ajax
     */
    private $ajax;

    /**
     * Flag indicating state of query cache for records retrieval. This value is passed to Query object when it is
     * prepared. Default value is false
     * @var bool
     */
    private $useQueryCache = false;
    /**
     * Flag indicating state of query cache for records counting. This value is passed to Query object when it is
     * created. Default value is false
     * @var bool
     */
    private $useCountQueryCache = false;
    /**
     * Arguments to pass when configuring result cache on query for records retrieval. Those arguments are used when
     * calling useResultCache method on Query object when one is created.
     * @var array
     */
    private $useResultCacheArgs = [false];
    /**
     * Arguments to pass when configuring result cache on query for counting records. Those arguments are used when
     * calling useResultCache method on Query object when one is created.
     * @var array
     */
    private $useCountResultCacheArgs = [false];

    /** @var Criteria */
    private $baseCriteria = null;

    //-------------------------------------------------
    // Ctor. && Init column arrays
    //-------------------------------------------------

    /**
     * DatatableQuery constructor.
     *
     * @param array              $requestParams
     * @param DatatableInterface $datatable
     *
     * @throws Exception
     */
    public function __construct(array $requestParams, DatatableInterface $datatable)
    {
        $this->requestParams = $requestParams;

        $this->em = $datatable->getEntityManager();
        $this->entityName = $datatable->getEntity();

        $this->metadata = $this->getMetadata($this->entityName);
        $this->entityShortName = $this->getEntityShortName($this->metadata);
        $this->rootEntityIdentifier = $this->getIdentifier($this->metadata);

        $this->qb = $this->em->createQueryBuilder();
        $this->accessor = PropertyAccess::createPropertyAccessor();

        $this->columns = $datatable->getColumnBuilder()->getColumns();

        $this->selectColumns = array();
        $this->searchColumns = array();
        $this->orderColumns = array();
        $this->joins = array();

        $this->options = $datatable->getOptions();
        $this->features = $datatable->getFeatures();
        $this->ajax = $datatable->getAjax();

        $this->baseCriteria = $datatable->getCriteria();

        $this->initColumnArrays();
    }

    /**
     * Init column arrays for select, search, order and joins.
     *
     * @return $this
     */
    private function initColumnArrays()
    {
        foreach ($this->columns as $key => $column) {
            $dql = $this->accessor->getValue($column, 'dql');
            $data = $this->accessor->getValue($column, 'data');

            $currentPart = $this->entityShortName;
            $currentAlias = $currentPart;
            $metadata = $this->metadata;

            if (true === $this->accessor->getValue($column, 'customDql')) {
                $columnAlias = str_replace('.', '_', $data);

                // Select
                $selectDql = preg_replace('/\{([\w]+)\}/', '$1', $dql);
                $this->addSelectColumn(null, $selectDql.' '.$columnAlias);
                // Order on alias column name
                $this->addOrderColumn($column, null, $columnAlias);
                // Fix subqueries alias duplication
                $searchDql = preg_replace('/\{([\w]+)\}/', '$1_search', $dql);
                $this->addSearchColumn($column, null, $searchDql);
            } elseif (true === $this->accessor->getValue($column, 'selectColumn')) {
                $parts = explode('.', $dql);

                while (count($parts) > 1) {
                    $previousPart = $currentPart;
                    $previousAlias = $currentAlias;

                    $currentPart = array_shift($parts);
                    $currentAlias = ($previousPart === $this->entityShortName ? '' : $previousPart.'_').$currentPart;

                    if (!array_key_exists($previousAlias.'.'.$currentPart, $this->joins)) {
                        $this->addJoin($previousAlias.'.'.$currentPart, $currentAlias, $this->accessor->getValue($column, 'joinType'));
                    }

                    $metadata = $this->setIdentifierFromAssociation($currentAlias, $currentPart, $metadata);
                }

                $this->addSelectColumn($currentAlias, $this->getIdentifier($metadata));
                $this->addSelectColumn($currentAlias, $parts[0]);
                $this->addSearchOrderColumn($column, $currentAlias, $parts[0]);
            } else {
                // Add Order-Field for VirtualColumn
                if ($this->accessor->isReadable($column, 'orderColumn') && true === $this->accessor->getValue($column, 'orderable')) {
                    $orderColumn = $this->accessor->getValue($column, 'orderColumn');
                    $orderParts = explode('.', $orderColumn);
                    if (count($orderParts) < 2) {
                        $orderColumn = $this->entityShortName.'.'.$orderColumn;
                    }
                    $this->orderColumns[] = $orderColumn;
                } else {
                    $this->orderColumns[] = null;
                }

                // Add Search-Field for VirtualColumn
                if ($this->accessor->isReadable($column, 'searchColumn') && true === $this->accessor->getValue($column, 'searchable')) {
                    $searchColumn = $this->accessor->getValue($column, 'searchColumn');
                    $searchParts = explode('.', $searchColumn);
                    if (count($searchParts) < 2) {
                        $searchColumn = $this->entityShortName.'.'.$searchColumn;
                    }
                    $this->searchColumns[] = $searchColumn;
                } else {
                    $this->searchColumns[] = null;
                }
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
        $this->setWhere($this->qb);
        $this->addCriteria($this->qb);
        $this->setOrderBy();
        $this->setLimit();

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

    //-------------------------------------------------
    // Private/Public - Setup query
    //-------------------------------------------------

    /**
     * Set select from.
     *
     * @return $this
     */
    private function setSelectFrom()
    {
        foreach ($this->selectColumns as $key => $value) {
            if (false === empty($key)) {
                $this->qb->addSelect('partial '.$key.'.{'.implode(',', $value).'}');
            } else {
                $this->qb->addSelect($value);
            }
        }

        $this->qb->from($this->entityName, $this->entityShortName);

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
            $qb->{$value['type']}($key, $value['alias']);
        }

        return $this;
    }

    /**
     * Searching / Filtering.
     * Construct the WHERE clause for server-side processing SQL query.
     *
     * @param QueryBuilder $qb
     *
     * @return $this
     */
    private function setWhere(QueryBuilder $qb)
    {
        // global filtering
        if (isset($this->requestParams['search']) && '' != $this->requestParams['search']['value']) {
            $globalSearch = $this->requestParams['search']['value'];

            $orExpr = $qb->expr()->orX();
            $globalSearchType = $this->options->getGlobalSearchType();

            foreach ($this->columns as $key => $column) {
                if (true === $this->isSearchableColumn($column)) {
                    $searchType = $globalSearchType;
                    $searchField = $this->searchColumns[$key];
                    // Subqueries and arithmetics fields can't be search with LIKE
                    if ($column->isCustomDql() && ('string' != $column->getTypeOfField()) || preg_match('/SELECT .+ FROM .+/', $searchField)) {
                        if (!is_numeric($globalSearch)) {
                            continue;
                        }
                        $globalSearch = floatval($globalSearch);
                        switch ($searchType) {
                            case 'like':
                                $searchType = 'eq';
                                break;
                            case 'notLike':
                                $searchType = 'neq';
                                break;
                        }
                    }
                    $this->setOrExpression($orExpr, $qb, $searchType, $searchField, $globalSearch, $key);
                }
            }

            if ($orExpr->count() > 0) {
                $qb->where($orExpr);
            }
        }

        // individual filtering
        if (true === $this->accessor->getValue($this->options, 'individualFiltering')) {
            $andExpr = $qb->expr()->andX();

            $parameterCounter = DatatableQueryBuilder::INIT_PARAMETER_COUNTER;

            foreach ($this->columns as $key => $column) {
                if (true === $this->isSearchableColumn($column)) {
                    if (false === array_key_exists($key, $this->requestParams['columns'])) {
                        continue;
                    }

                    $searchValue = $this->requestParams['columns'][$key]['search']['value'];

                    if ('' != $searchValue && 'null' != $searchValue) {
                        /** @var FilterInterface $filter */
                        $filter = $this->accessor->getValue($column, 'filter');
                        $searchField = $this->searchColumns[$key];
                        $andExpr = $filter->addAndExpression($andExpr, $qb, $searchField, $searchValue, $parameterCounter);
                    }
                }
            }

            if ($andExpr->count() > 0) {
                $qb->andWhere($andExpr);
            }
        }

        return $this;
    }

    /**
     * Ordering.
     * Construct the ORDER BY clause for server-side processing SQL query.
     *
     * @return $this
     */
    private function setOrderBy()
    {
        if (isset($this->requestParams['order']) && count($this->requestParams['order'])) {
            $counter = count($this->requestParams['order']);

            for ($i = 0; $i < $counter; $i++) {
                $columnIdx = (int) $this->requestParams['order'][$i]['column'];
                $requestColumn = $this->requestParams['columns'][$columnIdx];

                if ('true' == $requestColumn['orderable']) {
                    $columnName = $this->orderColumns[$columnIdx];
                    $orderDirection = $this->requestParams['order'][$i]['dir'];

                    $this->qb->addOrderBy($columnName, $orderDirection);
                }
            }
        }

        return $this;
    }

    /**
     * Paging.
     * Construct the LIMIT clause for server-side processing SQL query.
     *
     * @return $this
     * @throws Exception
     */
    private function setLimit()
    {
        if (true === $this->features->getPaging() || null === $this->features->getPaging()) {
            if (isset($this->requestParams['start']) && DatatableQueryBuilder::DISABLE_PAGINATION != $this->requestParams['length']) {
                $this->qb->setFirstResult($this->requestParams['start'])->setMaxResults($this->requestParams['length']);
            }
        } elseif ($this->ajax->getPipeline() > 0) {
            throw new Exception('DatatableQueryBuilder::setLimit(): For disabled paging, the ajax Pipeline-Option must be turned off.');
        }

        return $this;
    }

    /**
     * Constructs a Query instance.
     *
     * @return Query
     */
    public function execute()
    {
        $query = $this->qb->getQuery();
        $query->setHydrationMode(Query::HYDRATE_ARRAY)
            ->useQueryCache($this->useQueryCache);
        call_user_func_array([$query, 'useResultCache'], $this->useResultCacheArgs);

        return $query;
    }

    /**
     * Query results before filtering.
     *
     * @return int
     */
    public function getCountAllResults()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(distinct '.$this->entityShortName.'.'.$this->rootEntityIdentifier.')');
        $qb->from($this->entityName, $this->entityShortName);

        $this->addCriteria($qb);

        $query = $qb->getQuery();
        $query->useQueryCache($this->useCountQueryCache);
        call_user_func_array([$query, 'useResultCache'], $this->useCountResultCacheArgs);

        return !$qb->getDQLPart('groupBy') ?
            (int) $query->getSingleScalarResult()
            : count($query->getResult());
    }

    /**
     * Defines whether query used for records retrieval should use query cache if available.
     *
     * @param bool $bool
     *
     * @return $this
     */
    public function useQueryCache($bool)
    {
        $this->useQueryCache = $bool;

        return $this;
    }

    /**
     * Defines whether query used for counting records should use query cache if available.
     *
     * @param bool $bool
     *
     * @return $this
     */
    public function useCountQueryCache($bool)
    {
        $this->useCountQueryCache = $bool;

        return $this;
    }

    /**
     * Set wheter or not to cache result of records retrieval query and if so, for how long and under which ID. Method is
     * consistent with {@see \Doctrine\ORM\AbstractQuery::useResultCache} method.
     *
     * @param bool        $bool          flag defining whether use caching or not
     * @param int|null    $lifetime      lifetime of cache in seconds
     * @param string|null $resultCacheId string identifier for result cache if left empty ID will be generated by Doctrine
     *
     * @return $this
     */
    public function useResultCache($bool, $lifetime = null, $resultCacheId = null)
    {
        $this->useResultCacheArgs = func_get_args();

        return $this;
    }

    /**
     * Set wheter or not to cache result of records counting query and if so, for how long and under which ID. Method is
     * consistent with {@see \Doctrine\ORM\AbstractQuery::useResultCache} method.
     *
     * @param boolean     $bool          flag defining whether use caching or not
     * @param int|null    $lifetime      lifetime of cache in seconds
     * @param string|null $resultCacheId string identifier for result cache if left empty ID will be generated by Doctrine
     *
     * @return $this
     */
    public function useCountResultCache($bool, $lifetime = null, $resultCacheId = null)
    {
        $this->useCountResultCacheArgs = func_get_args();

        return $this;
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
     * Add order column.
     *
     * @param object $column
     * @param string $columnTableName
     * @param string $data
     *
     * @return $this
     */
    private function addOrderColumn($column, $columnTableName, $data)
    {
        true === $this->accessor->getValue($column, 'orderable') ? $this->orderColumns[] = ($columnTableName ? $columnTableName.'.' : '').$data : $this->orderColumns[] = null;

        return $this;
    }

    /**
     * Add search column.
     *
     * @param object $column
     * @param string $columnTableName
     * @param string $data
     *
     * @return $this
     */
    private function addSearchColumn($column, $columnTableName, $data)
    {
        true === $this->accessor->getValue($column, 'searchable') ? $this->searchColumns[] = ($columnTableName ? $columnTableName.'.' : '').$data : $this->searchColumns[] = null;

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
        $this->addOrderColumn($column, $columnTableName, $data);
        $this->addSearchColumn($column, $columnTableName, $data);

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
            'type' => $type,
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
            throw new Exception('DatatableQueryBuilder::getMetadata(): Given object '.$entityName.' is not a Doctrine Entity.');
        }

        return $metadata;
    }

    /**
     * Get entity short name.
     *
     * @param ClassMetadata $metadata
     *
     * @return string
     */
    private function getEntityShortName(ClassMetadata $metadata)
    {
        return strtolower($metadata->getReflectionClass()->getShortName());
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

    /**
     * Is searchable column.
     *
     * @param ColumnInterface $column
     *
     * @return bool
     */
    private function isSearchableColumn(ColumnInterface $column)
    {
        $searchColumn = null !== $this->accessor->getValue($column, 'dql') && true === $this->accessor->getValue($column, 'searchable');

        if (false === $this->options->isSearchInNonVisibleColumns()) {
            return $searchColumn && true === $this->accessor->getValue($column, 'visible');
        }

        return $searchColumn;
    }

    /**
     * Set Orx Expression.
     *
     * @param Orx          $orExpr
     * @param QueryBuilder $qb
     * @param string       $searchType
     * @param string       $searchField
     * @param mixed        $searchValue
     * @param integer      $key
     *
     * @return $this
     */
    private function setOrExpression(Orx $orExpr, QueryBuilder $qb, $searchType, $searchField, $searchValue, $key)
    {
        switch ($searchType) {
            case 'like':
                $orExpr->add($qb->expr()->like($searchField, '?'.$key));
                $qb->setParameter($key, '%'.$searchValue.'%');
                break;
            case 'notLike':
                $orExpr->add($qb->expr()->notLike($searchField, '?'.$key));
                $qb->setParameter($key, '%'.$searchValue.'%');
                break;
            case 'eq':
                $orExpr->add($qb->expr()->eq($searchField, '?'.$key));
                $qb->setParameter($key, $searchValue);
                break;
            case 'neq':
                $orExpr->add($qb->expr()->neq($searchField, '?'.$key));
                $qb->setParameter($key, $searchValue);
                break;
            case 'lt':
                $orExpr->add($qb->expr()->lt($searchField, '?'.$key));
                $qb->setParameter($key, $searchValue);
                break;
            case 'lte':
                $orExpr->add($qb->expr()->lte($searchField, '?'.$key));
                $qb->setParameter($key, $searchValue);
                break;
            case 'gt':
                $orExpr->add($qb->expr()->gt($searchField, '?'.$key));
                $qb->setParameter($key, $searchValue);
                break;
            case 'gte':
                $orExpr->add($qb->expr()->gte($searchField, '?'.$key));
                $qb->setParameter($key, $searchValue);
                break;
            case 'in':
                $orExpr->add($qb->expr()->in($searchField, '?'.$key));
                $qb->setParameter($key, explode(',', $searchValue));
                break;
            case 'notIn':
                $orExpr->add($qb->expr()->notIn($searchField, '?'.$key));
                $qb->setParameter($key, explode(',', $searchValue));
                break;
            case 'isNull':
                $orExpr->add($qb->expr()->isNull($searchField));
                break;
            case 'isNotNull':
                $orExpr->add($qb->expr()->isNotNull($searchField));
                break;
        }

        return $this;
    }

    /**
     * @param Criteria $baseCriteria
     * @return DatatableQueryBuilder
     */
    public function setBaseCriteria($baseCriteria)
    {
        $this->baseCriteria = $baseCriteria;
        return $this;
    }

    private function addCriteria(QueryBuilder $qb)
    {
        if($this->baseCriteria !== null) {
            $qb->addCriteria($this->baseCriteria);
        }
    }
}
