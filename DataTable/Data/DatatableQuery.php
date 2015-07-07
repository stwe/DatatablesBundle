<?php

/**
 * This file is part of the WgUniversalDataTableBundle package.
 *
 * (c) stwe <https://github.com/stwe/DataTablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wg\UniversalDataTable\DataTable\Data;

use Wg\UniversalDataTable\DataTable\View\DataTableViewInterface;
use Wg\UniversalDataTable\DataTable\Column\AbstractColumn;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;

/**
 * Class DataTableQuery
 *
 * @package Wg\UniversalDataTable\DataTable\Data
 */
class DataTableQuery
{
    /**
     * @var Serializer
     */
    private $serializer;

    /**
     * @var array
     */
    private $requestParams;

    /**
     * @var DataTableViewInterface
     */
    private $datatableView;

    /**
     * @var string
     */
    private $entity;

    /**
     * @var boolean
     */
    private $individualFiltering;

    /**
     * @var EntityManager
     */
    private $em;

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
     * @var array
     */
    private $selectColumns;

    /**
     * @var array
     */
    private $virtualColumns;

    /**
     * @var array
     */
    private $joins;

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
    private $callbacks;

    /**
     * @var callable
     */
    private $lineFormatter;

    /**
     * @var AbstractColumn[]
     */
    private $columns;

    /**
     * @var array
     */
    protected $order = array();

    /**
     * @var array
     */
    protected $select = array();

    /**
     * @var array
     */
    protected $disabledCoreSearch = array();

    /**
     * Ctor.
     *
     * @param Serializer             $serializer
     * @param array                  $requestParams
     * @param DataTableViewInterface $datatableView
     * @param array                  $disabledCoreSearch
     */
    public function __construct(Serializer $serializer, array $requestParams, DataTableViewInterface $datatableView, $disabledCoreSearch)
    {
        $this->serializer = $serializer;
        $this->requestParams = $requestParams;
        $this->datatableView = $datatableView;

        $this->individualFiltering = $this->datatableView->getOptions()->getIndividualFiltering();

        $this->entity = $this->datatableView->getEntity();
        $this->em = $this->datatableView->getEntityManager();
        $metadata = $this->getMetadata($this->entity);
        $this->tableName = $this->getTableName($metadata);
        $this->rootEntityIdentifier = $this->getIdentifier($metadata);
        $this->qb = $this->em->createQueryBuilder();

        $this->selectColumns = array();
        $this->virtualColumns = $datatableView->getColumnBuilder()->getVirtualColumns();
        $this->joins = array();
        $this->searchColumns = array();
        $this->orderColumns = array();
        $this->callbacks = array();
        $this->columns = $datatableView->getColumnBuilder()->getColumns();

        $this->setLineFormatter();
        $this->setupColumnArrays();

        $this->disabledCoreSearch = $disabledCoreSearch;
    }

    //-------------------------------------------------
    // Setup query
    //-------------------------------------------------

    /**
     * Setup column arrays.
     *
     * @return $this
     */
    private function setupColumnArrays()
    {
        $this->selectColumns[$this->tableName][] = $this->rootEntityIdentifier;

        foreach ($this->columns as $key => $column) {
            $data = $column->getDql();

            if (true === $this->isSelectColumn($data)) {
                if (false === $this->isAssociation($data)) {
                    $this->addSearchOrderColumn($key, $this->tableName, $data);
                    if ($data !== $this->rootEntityIdentifier) {
                        $this->selectColumns[$this->tableName][] = $data;
                    }
                } else {
                    $array = explode('.', $data);
                    $count = count($array);

                    if ($count > 2) {
                        $replaced = str_replace('.', '_', $data);
                        $parts = explode('_', $replaced);
                        $last = array_pop($parts);
                        $select = implode('_', $parts);
                        $join = str_replace('_', '.', $select);
                        $this->selectColumns[$select][] = $last;

                        $this->selectColumns[$array[0]][] = 'id';
                        $this->joins[$this->tableName . '.' . $array[0]] = $array[0];

                        $this->joins[$join] = $select;
                        $this->addSearchOrderColumn($key, $select, $last);
                    } else {
                        $this->selectColumns[$array[0]][] = $array[1];
                        $this->joins[$this->tableName . '.' . $array[0]] = $array[0];
                        $this->addSearchOrderColumn($key, $array[0], $array[1]);
                    }
                }
            } else {
                $this->orderColumns[] = null;
                $this->searchColumns[] = null;
            }

        }

        // joins - hardcoded id's
        foreach ($this->selectColumns as $key => $value) {
            $array = $this->selectColumns[$key];

            if (false === array_search('id', $array)) {
                $array[] = 'id';
            }

            $array = array_unique($array);

            $this->selectColumns[$key] = $array;
        }

        return $this;
    }

    /**
     * Build query.
     *
     * @return $this
     */
    public function buildQuery()
    {
        $this->setSelectFrom();
        $this->setLeftJoins($this->qb);
        $this->setWhere($this->qb);
        $this->setWhereResultCallback($this->qb);
        $this->setWhereAllCallback($this->qb);
        $this->setOrderBy();
        $this->setLimit();

        return $this;
    }

    /**
     * Get query.
     *
     * @return QueryBuilder
     */
    public function getQuery()
    {
        return $this->qb;
    }

    /**
     * Set query.
     *
     * @param QueryBuilder $qb
     *
     * @return $this
     */
    public function setQuery(QueryBuilder $qb)
    {
        $this->qb = $qb;

        return $this;
    }

    //-------------------------------------------------
    // Callbacks
    //-------------------------------------------------

    /**
     * Add the where-result function.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function addWhereResult(callable $callback)
    {
        $this->callbacks['WhereResult'] = $callback;

        return $this;
    }

    /**
     * Add the where-all function.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function addWhereAll(callable $callback)
    {
        $this->callbacks['WhereAll'] = $callback;

        return $this;
    }

    /**
     * Set the line formatter function.
     *
     * @return $this
     */
    private function setLineFormatter()
    {
        $this->lineFormatter = $this->datatableView->getLineFormatter();

        return $this;
    }

    /**
     * Set where result callback.
     *
     * @param QueryBuilder $qb
     *
     * @return $this
     */
    private function setWhereResultCallback(QueryBuilder $qb)
    {
        if (!empty($this->callbacks['WhereResult'])) {
            $this->callbacks['WhereResult']($qb);
        }

        return $this;
    }

    /**
     * Set where all callback.
     *
     * @param QueryBuilder $qb
     *
     * @return $this
     */
    private function setWhereAllCallback(QueryBuilder $qb)
    {
        if (!empty($this->callbacks['WhereAll'])) {
            $this->callbacks['WhereAll']($qb);
        }

        return $this;
    }

    //-------------------------------------------------
    // Build a query
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

        foreach ($this->select as $sel) {
            $this->qb->addSelect($sel);
        }

        $this->qb->from($this->entity, $this->tableName);

        return $this;
    }

    /**
     * Set leftJoins.
     *
     * @param QueryBuilder $qb
     *
     * @return $this
     */
    private function setLeftJoins(QueryBuilder $qb)
    {
        foreach ($this->joins as $key => $value) {
            $qb->leftJoin($key, $value);
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
        $globalSearch = $this->requestParams['search']['value'];

        // global filtering
        if ('' != $globalSearch) {

            $orExpr = $qb->expr()->orX();

            foreach ($this->columns as $key => $column) {
                if (true === $this->isSearchColumn($column)) {
                    $searchField = $this->searchColumns[$key];
                    $orExpr->add($qb->expr()->like($searchField, '?' . $key));
                    $qb->setParameter($key, '%' . $globalSearch . '%');
                }
            }

            $qb->where($orExpr);
        }

        // individual filtering
        if (true === $this->individualFiltering) {
            $andExpr = $qb->expr()->andX();

            $i = 100;

            foreach ($this->columns as $key => $column) {

                if (true === $this->isSearchColumn($column)) {
                    if( in_array($column, (array) $this->disabledCoreSearch) ) continue;

                    $searchType = $column->getSearchType();
                    $searchField = $this->searchColumns[$key];
                    $searchValue = $this->requestParams['columns'][$key]['search']['value'];
                    $searchRange = $this->requestParams['columns'][$key]['name'] === 'daterange';
                    if ('' != $searchValue && 'null' != $searchValue) {
                        if ($searchRange) {
                            list($_dateStart, $_dateEnd) = explode(' - ', $searchValue);
                            $dateStart = new \DateTime($_dateStart);
                            $dateEnd = new \DateTime($_dateEnd);
                            $dateEnd->setTime(23, 59, 59);

                            $k = $i + 1;
                            $andExpr->add($qb->expr()->between($searchField, '?' . $i, '?' . $k));
                            $qb->setParameter($i, $dateStart->format('Y-m-d H:i:s'));
                            $qb->setParameter($k, $dateEnd->format('Y-m-d H:i:s'));
                            $i += 2;
                        } else {
                            $andExpr = $this->addCondition($andExpr, $qb, $searchType, $searchField, $searchValue, $i);
                            $i++;
                        }
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
     * Add a condition.
     *
     * @param Andx         $andExpr
     * @param QueryBuilder $pivot
     * @param string       $searchType
     * @param string       $searchField
     * @param string       $searchValue
     * @param integer      $i
     *
     * @return Andx
     */
    private function addCondition(Andx $andExpr, QueryBuilder $pivot, $searchType, $searchField, $searchValue, $i)
    {
        switch ($searchType) {
            case 'like':
                $andExpr->add($pivot->expr()->like($searchField, '?' . $i));
                $pivot->setParameter($i, '%' . $searchValue . '%');
                break;
            case 'notLike':
                $andExpr->add($pivot->expr()->notLike($searchField, '?' . $i));
                $pivot->setParameter($i, '%' . $searchValue . '%');
                break;
            case 'eq':
                $andExpr->add($pivot->expr()->eq($searchField, '?' . $i));
                $pivot->setParameter($i, $searchValue);
                break;
            case 'neq':
                $andExpr->add($pivot->expr()->neq($searchField, '?' . $i));
                $pivot->setParameter($i, $searchValue);
                break;
            case 'lt':
                $andExpr->add($pivot->expr()->lt($searchField, '?' . $i));
                $pivot->setParameter($i, $searchValue);
                break;
            case 'lte':
                $andExpr->add($pivot->expr()->lte($searchField, '?' . $i));
                $pivot->setParameter($i, $searchValue);
                break;
            case 'gt':
                $andExpr->add($pivot->expr()->gt($searchField, '?' . $i));
                $pivot->setParameter($i, $searchValue);
                break;
            case 'gte':
                $andExpr->add($pivot->expr()->gte($searchField, '?' . $i));
                $pivot->setParameter($i, $searchValue);
                break;
            case 'in':
                $andExpr->add($pivot->expr()->in($searchField, '?' . $i));
                $pivot->setParameter($i, explode(',', $searchValue));
                break;
            case 'notIn':
                $andExpr->add($pivot->expr()->notIn($searchField, '?' . $i));
                $pivot->setParameter($i, explode(",", $searchValue));
                break;
            case 'isNull':
                $andExpr->add($pivot->expr()->isNull($searchField));
                break;
            case 'isNotNull':
                $andExpr->add($pivot->expr()->isNull($searchField));
                break;
        }

        return $andExpr;
    }

    /**
     * Ordering.
     * Construct the ORDER BY clause for server-side processing SQL query.
     *
     * @return $this
     */
    private function setOrderBy()
    {
        if( count($this->order) > 0 ) {
            foreach($this->order AS $column => $order) {
                $this->qb->addOrderBy($column, $order);
            }
        }

        if (isset($this->requestParams['order']) && count($this->requestParams['order'])) {

            $counter = count($this->requestParams['order']);

            for ($i = 0; $i < $counter; $i++) {
                $columnIdx = (integer) $this->requestParams['order'][$i]['column'];
                $requestColumn = $this->requestParams['columns'][$columnIdx];

                if ('true' == $requestColumn['orderable']) {
                    $this->qb->addOrderBy(
                        $this->orderColumns[$columnIdx],
                        $this->requestParams['order'][$i]['dir']
                    );
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
     */
    private function setLimit()
    {
        if (isset($this->requestParams['start']) && -1 != $this->requestParams['length']) {
            $this->qb->setFirstResult($this->requestParams['start'])->setMaxResults($this->requestParams['length']);
        }

        return $this;
    }

    //-------------------------------------------------
    // Results
    //-------------------------------------------------

    /**
     * Query results before filtering.
     *
     * @param integer $rootEntityIdentifier
     *
     * @return int
     */
    private function getCountAllResults($rootEntityIdentifier)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(distinct ' . $this->tableName . '.' . $rootEntityIdentifier . ')');
        $qb->from($this->entity, $this->tableName);

        $this->setLeftJoins($qb);
        $this->setWhereAllCallback($qb);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Query results after filtering.
     *
     * @param integer $rootEntityIdentifier
     *
     * @return int
     */
    private function getCountFilteredResults($rootEntityIdentifier)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(distinct ' . $this->tableName . '.' . $rootEntityIdentifier . ')');
        $qb->from($this->entity, $this->tableName);

        $this->setLeftJoins($qb);
        $this->setWhere($qb);
        $this->setWhereAllCallback($qb);

        return (int) $qb->getQuery()->getSingleScalarResult();
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
    // Response
    //-------------------------------------------------

    public function getResponse($buildQuery = true)
    {
        false === $buildQuery ? : $this->buildQuery();

        $fresults = new Paginator($this->execute(), true);
        $output = array('data' => array());

        foreach ($fresults as $item) {
            if (is_callable($this->lineFormatter)) {
                $callable = $this->lineFormatter;
                $item = call_user_func($callable, $item);
            }

            $output['data'][] = $item;
        }

        $outputHeader = array(
            'draw' => (int) $this->requestParams['draw'],
            'recordsTotal' => (int) $this->getCountAllResults($this->rootEntityIdentifier),
            'recordsFiltered' => (int) $this->getCountFilteredResults($this->rootEntityIdentifier)
        );

        $json = $this->serializer->serialize(array_merge($outputHeader, $output), 'json');
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Add search/order columns.
     *
     * @param integer $key
     * @param string  $columnTableName
     * @param string  $data
     */
    private function addSearchOrderColumn($key, $columnTableName, $data)
    {
        $column = $this->columns[$key];

        true === $column->getOrderable() ? $this->orderColumns[] = $columnTableName . '.' . $data : $this->orderColumns[] = null;
        true === $column->getSearchable() ? $this->searchColumns[] = $columnTableName . '.' . $data : $this->searchColumns[] = null;
    }

    /**
     * Get metadata.
     *
     * @param string $entity
     *
     * @return ClassMetadata
     * @throws Exception
     */
    private function getMetadata($entity)
    {
        try {
            $metadata = $this->em->getMetadataFactory()->getMetadataFor($entity);
        } catch (MappingException $e) {
            throw new Exception('getMetadata(): Given object ' . $entity . ' is not a Doctrine Entity.');
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

    /**
     * Is association.
     *
     * @param string $data
     *
     * @return bool|int
     */
    private function isAssociation($data)
    {
        return strpos($data, '.');
    }

    /**
     * Is select column.
     *
     * @param string $data
     *
     * @return bool
     */
    private function isSelectColumn($data)
    {
        if (null !== $data && !in_array($data, $this->virtualColumns)) {
            return true;
        }

        return false;
    }

    /**
     * Is search column.
     *
     * @param AbstractColumn $column
     *
     * @return bool
     */
    private function isSearchColumn(AbstractColumn $column)
    {
        if (null !== $column->getDql() && true === $column->getSearchable() && true === $column->getVisible()) {
            return true;
        }

        return false;
    }


    /**
     * @param $column
     * @param string $order
     */
    public function addOrder($column, $order = 'DESC')
    {
        $this->order[$column] = $order;
    }

    /**
     * @param $select
     */
    public function addSelect($select)
    {
        $this->select[] = $select;
    }
}
