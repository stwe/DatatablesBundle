<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Data;

use Sg\DatatablesBundle\Datatable\View\DatatableViewInterface;
use Sg\DatatablesBundle\Datatable\Column\AbstractColumn;

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
 * Class DatatableQuery
 *
 * @package Sg\DatatablesBundle\Datatable\Data
 */
class DatatableQuery
{
    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var array
     */
    protected $requestParams;

    /**
     * @var DatatableViewInterface
     */
    protected $datatableView;

    /**
     * @var string
     */
    protected $entity;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var ClassMetadata
     */
    protected $metadata;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var mixed
     */
    protected $rootEntityIdentifier;

    /**
     * @var QueryBuilder
     */
    protected $qb;

    /**
     * @var array
     */
    protected $selectColumns;

    /**
     * @var array
     */
    protected $virtualColumns;

    /**
     * @var array
     */
    protected $joins;

    /**
     * @var array
     */
    protected $searchColumns;

    /**
     * @var array
     */
    protected $orderColumns;

    /**
     * @var array
     */
    protected $callbacks;

    /**
     * @var callable
     */
    protected $lineFormatter;

    /**
     * @var AbstractColumn[]
     */
    protected $columns;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param Serializer             $serializer
     * @param array                  $requestParams
     * @param DatatableViewInterface $datatableView
     */
    public function __construct(Serializer $serializer, array $requestParams, DatatableViewInterface $datatableView)
    {
        $this->serializer = $serializer;
        $this->requestParams = $requestParams;
        $this->datatableView = $datatableView;

        $this->entity = $this->datatableView->getEntity();
        $this->em = $this->datatableView->getDoctrine()->getManager();
        $this->metadata = $this->getMetadata($this->entity);
        $this->tableName = $this->getTableName($this->metadata);
        $this->rootEntityIdentifier = $this->getIdentifier($this->metadata);
        $this->qb = $this->em->createQueryBuilder();

        $this->selectColumns = array();
        $this->virtualColumns = $datatableView->getColumnBuilder()->getVirtualColumns();
        $this->joins = array();
        $this->searchColumns = array();
        $this->orderColumns = array();
        $this->callbacks = array();
        $this->columns = $datatableView->getColumnBuilder()->getColumns();

        $this->setLineFormatterCallback();
        $this->setupColumnArrays();
    }

    //-------------------------------------------------
    // Setup
    //-------------------------------------------------

    /* Example:
    $qb = $this->em->createQueryBuilder();
    $qb->select("
         partial post.{id, title},
         partial comments.{id, title},
         partial createdby.{id, username},
         partial updatedby.{id, username}");
    $qb->from("AppBundle:Post", "post");
    $qb->leftJoin("post.comments", "comments");
    $qb->leftJoin("comments.createdby", "createdby");
    $qb->leftJoin("comments.updatedby", "updatedby");
    $query = $qb->getQuery();
    $results = $query->getArrayResult();
    */

    /**
     * Setup column arrays.
     *
     * @return $this
     */
    private function setupColumnArrays()
    {
        // Start with the tableName and the primary key e.g. "post" and "id"
        $this->addSelectColumn($this->metadata, $this->rootEntityIdentifier, $this->tableName);

        foreach ($this->columns as $key => $column) {
            // Get the column data source
            $data = $column->getDql();

            if (false === $this->isAssociation($data)) {
                $this->addSearchOrderColumn($key, $this->tableName, $data);
            }

            if (null !== $data && !in_array($data, $this->virtualColumns)) {
                // Association found (e.g. "comments.title")?
                if (false !== $this->isAssociation($data)) {
                    $array = explode(".", $data);
                    $this->setAssociations($array, 0, $this->metadata, $this->tableName, $key);
                } else {
                    if ($data !== $this->rootEntityIdentifier) {
                        $this->addSelectColumn($this->metadata, $data, $this->tableName);
                    }
                }
            }
        }

        return $this;
    }

    /**
     * Add select columns.
     *
     * @param ClassMetadata $metadata
     * @param string        $data
     * @param string        $columnTableName
     *
     * @return $this
     * @throws Exception
     */
    private function addSelectColumn(ClassMetadata $metadata, $data, $columnTableName)
    {
        if (in_array($data, $metadata->getFieldNames())) {
            $this->selectColumns[$columnTableName][] = $data;
        } else {
            throw new Exception("Exception when parsing the columns.");
        }

        return $this;
    }

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

        true === $column->getOrderable() ? $this->orderColumns[] = $columnTableName . "." . $data : $this->orderColumns[] = null;
        true === $column->getSearchable() ? $this->searchColumns[] = $columnTableName . "." . $data : $this->searchColumns[] = null;
    }

    /**
     * Set associations.
     *
     * @param array         $associationParts
     * @param integer       $i
     * @param ClassMetadata $metadata
     * @param string        $lastTableName
     * @param integer       $key
     *
     * @return $this
     * @throws Exception
     */
    private function setAssociations(array $associationParts, $i, ClassMetadata $metadata, $lastTableName, $key)
    {
        $column = $associationParts[$i];

        if (true === $metadata->hasAssociation($column)) {
            $targetClass = $metadata->getAssociationTargetClass($column);
            $targetMeta = $this->getMetadata($targetClass);
            $targetRootIdentifier = $this->getIdentifier($targetMeta);
            $targetTableName = $column;

            if (!array_key_exists($targetTableName, $this->selectColumns)) {
                // add the target primary key e.g. 'comments' => 'id'
                $this->addSelectColumn($targetMeta, $targetRootIdentifier, $targetTableName);
                $this->joins[] = array("source" => $lastTableName . "." . $column, "target" => $targetTableName);
            }

            $i++;
            $this->setAssociations($associationParts, $i, $targetMeta, $targetTableName, $key);
        } else {
            $targetRootIdentifier = $this->getIdentifier($metadata);

            if ($column !== $targetRootIdentifier) {
                $this->addSelectColumn($metadata, $column, $lastTableName);
                $this->addSearchOrderColumn($key, $lastTableName, $column);
            }
        }

        return $this;
    }

    /**
     * Build query.
     *
     * @return $this
     */
    private function buildQuery()
    {
        $this->setSelectFrom();
        $this->setLeftJoins($this->qb);
        $this->setWhere($this->qb);
        $this->setOrderBy();
        $this->setLimit();

        return $this;
    }

    //-------------------------------------------------
    // Callbacks
    //-------------------------------------------------

    /**
     * Add where result.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function addWhereResult(callable $callback)
    {
        $this->callbacks["WhereResult"][] = $callback;

        return $this;
    }

    /**
     * Add where all.
     *
     * @param callable $callback
     *
     * @return $this
     */
    public function addWhereAll(callable $callback)
    {
        $this->callbacks["WhereAll"][] = $callback;

        return $this;
    }

    /**
     * Set the line formatter function.
     *
     * @return $this
     */
    private function setLineFormatterCallback()
    {
        $this->lineFormatter = $this->datatableView->getLineFormatter();

        return $this;
    }

    /**
     * Set where result.
     *
     * @param QueryBuilder $qb
     *
     * @return $this
     */
    private function setWhereResultCallbacks(QueryBuilder $qb)
    {
        if (!empty($this->callbacks["WhereResult"])) {
            foreach ($this->callbacks["WhereResult"] as $callback) {
                $callback($qb);
            }
        }

        return $this;
    }

    /**
     * Set where all.
     *
     * @param QueryBuilder $qb
     *
     * @return $this
     */
    private function setWhereAllCallbacks(QueryBuilder $qb)
    {
        if (!empty($this->callbacks["WhereAll"])) {
            foreach ($this->callbacks["WhereAll"] as $callback) {
                $callback($qb);
            }
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
            $this->qb->addSelect("partial " . $key . ".{" . implode(",", $this->selectColumns[$key]) . "}");
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
        foreach ($this->joins as $join) {
            $qb->leftJoin($join["source"], $join["target"]);
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
        $globalSearch = $this->requestParams["search"]["value"];

        // global filtering
        if ("" != $globalSearch) {

            $orExpr = $qb->expr()->orX();

            foreach ($this->searchColumns as $key => $column) {
                //var_dump($this->searchColumns); die();
                if (null !== $this->searchColumns[$key]) {
                    $searchField = $this->searchColumns[$key];
                    $orExpr->add($qb->expr()->like($searchField, "?$key"));
                    $qb->setParameter($key, "%" . $globalSearch . "%");
                }
            }

            $qb->where($orExpr);
        }

        // individual filtering
        $andExpr = $qb->expr()->andX();

        $i = 100;

        foreach ($this->searchColumns as $key => $column) {
            if (null !== $this->searchColumns[$key]) {
                $searchType = $this->columns[$key]->getSearchType();
                $searchField = $this->searchColumns[$key];
                $searchValue = $this->requestParams["columns"][$key]["search"]["value"];
                $searchRange = $this->requestParams["columns"][$key]["name"] === "daterange";
                if ("" != $searchValue && "null" != $searchValue) {
                    if ($searchRange) {
                        list($_dateStart, $_dateEnd) = explode(' - ', $searchValue);
                        $dateStart = new \DateTime($_dateStart);
                        $dateEnd = new \DateTime($_dateEnd);
                        $dateEnd->setTime(23, 59, 59);

                        $k = $i+1;
                        $andExpr->add($qb->expr()->between($searchField, "?$i", "?$k"));
                        $qb->setParameter($i, $dateStart->format('Y-m-d H:i:s'));
                        $qb->setParameter($k, $dateEnd->format('Y-m-d H:i:s'));
                        $i+=2;
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
            case "like":
                $andExpr->add($pivot->expr()->like($searchField, "?$i"));
                $pivot->setParameter($i, "%" . $searchValue . "%");
                break;
            case "notLike":
                $andExpr->add($pivot->expr()->notLike($searchField, "?$i"));
                $pivot->setParameter($i, "%" . $searchValue . "%");
                break;
            case "eq":
                $andExpr->add($pivot->expr()->eq($searchField, "?$i"));
                $pivot->setParameter($i, $searchValue);
                break;
            case "neq":
                $andExpr->add($pivot->expr()->neq($searchField, "?$i"));
                $pivot->setParameter($i, $searchValue);
                break;
            case "lt":
                $andExpr->add($pivot->expr()->lt($searchField, "?$i"));
                $pivot->setParameter($i, $searchValue);
                break;
            case "lte":
                $andExpr->add($pivot->expr()->lte($searchField, "?$i"));
                $pivot->setParameter($i, $searchValue);
                break;
            case "gt":
                $andExpr->add($pivot->expr()->gt($searchField, "?$i"));
                $pivot->setParameter($i, $searchValue);
                break;
            case "gte":
                $andExpr->add($pivot->expr()->gte($searchField, "?$i"));
                $pivot->setParameter($i, $searchValue);
                break;
            case "in":
                $andExpr->add($pivot->expr()->in($searchField, "?$i"));
                $pivot->setParameter($i, explode(',', $searchValue));
                break;
            case "notIn":
                $andExpr->add($pivot->expr()->notIn($searchField, "?$i"));
                $pivot->setParameter($i, explode(",", $searchValue));
                break;
            case "isNull":
                $andExpr->add($pivot->expr()->isNull($searchField));
                break;
            case "isNotNull":
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
        if (isset($this->requestParams["order"]) && count($this->requestParams["order"])) {

            $counter = count($this->requestParams["order"]);

            for ($i = 0; $i < $counter; $i++) {
                $columnIdx = (integer) $this->requestParams["order"][$i]["column"];
                $requestColumn = $this->requestParams["columns"][$columnIdx];

                if ("true" == $requestColumn["orderable"]) {
                    $this->qb->addOrderBy(
                        $this->orderColumns[$columnIdx],
                        $this->requestParams["order"][$i]["dir"]
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
        if (isset($this->requestParams["start"]) && -1 != $this->requestParams["length"]) {
            $this->qb->setFirstResult($this->requestParams["start"])->setMaxResults($this->requestParams["length"]);
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
        $qb->select("count(" . $this->tableName . "." . $rootEntityIdentifier . ")");
        $qb->from($this->entity, $this->tableName);

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
        $qb->select("count(distinct " . $this->tableName . "." . $rootEntityIdentifier . ")");
        $qb->from($this->entity, $this->tableName);

        $this->setLeftJoins($qb);
        $this->setWhere($qb);
        //$this->setCallbacks($qb);

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

    public function getResponse()
    {
        $this->buildQuery();

        $fresults = new Paginator($this->execute(), true);
        $output = array("data" => array());

        foreach ($fresults as $item) {
            if (is_callable($this->lineFormatter)) {
                $callable = $this->lineFormatter;
                $item = call_user_func($callable, $item);
            }

            $output["data"][] = $item;
        }

        $outputHeader = array(
            "draw" => (integer) $this->requestParams["draw"],
            "recordsTotal" => (integer) $this->getCountAllResults($this->rootEntityIdentifier),
            "recordsFiltered" => (integer) $this->getCountFilteredResults($this->rootEntityIdentifier)
        );

        $json = $this->serializer->serialize(array_merge($outputHeader, $output), "json");
        $response = new Response($json);
        $response->headers->set("Content-Type", "application/json");

        return $response;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

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
            throw new Exception("Given object {$entity} is not a Doctrine Entity. ");
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
        return strpos($data, ".");
    }

    /**
     * Dump query.
     *
     * @param bool $die
     *
     * @return $this
     */
    private function dumpQuery($die = false)
    {
        var_dump($this->qb->getDQL());

        if ($die) die();

        return $this;
    }
}
