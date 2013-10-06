<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Bridge\Monolog\Logger;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class DatatableData
 *
 * A thanks goes to the authors of the original implementation:
 *     Félix-Antoine Paradis (https://gist.github.com/reel/1638094) and
 *     Chad Sikorra (https://github.com/LanKit/DatatablesBundle)
 *
 * @package Sg\DatatablesBundle\Datatable
 */
class DatatableData implements DatatableDataInterface
{
    /**
     * @var array
     */
    protected $requestParams;

    /**
     * @var ClassMetadata
     */
    protected $metadata;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var Serializer
     */
    protected $serializer;

    /**
     * @var Logger
     */
    protected $logger;

    /**
     * Information for DataTables to use for rendering.
     *
     * @var int
     */
    protected $sEcho;

    /**
     * Global search field
     *
     * @var string
     */
    protected $sSearch;

    /**
     * Display start point in the current data set.
     *
     * @var int
     */
    protected $iDisplayStart;

    /**
     * Number of records that the table can display in the current draw.
     *
     * @var int
     */
    protected $iDisplayLength;

    /**
     * True if the global filter should be treated as a regular expression for advanced filtering, false if not.
     *
     * @var boolean
     */
    protected $bRegex;

    /**
     * Number of columns being displayed.
     *
     * @var int
     */
    protected $iColumns;

    /**
     * Number of columns to sort on.
     *
     * @var int
     */
    protected $iSortingCols;

    /**
     * Column being sorted on.
     *
     * @var int
     */
    protected $iSortCol0;

    /**
     * Direction to be sorted - "desc" or "asc".
     *
     * @var string
     */
    protected $sSortDir0;

    /**
     * @var string
     */
    protected $tableName;

    /**
     * @var array
     */
    protected $selectFields;

    /**
     * @var array
     */
    protected $allFields;

    /**
     * @var array
     */
    protected $joins;

    /**
     * @var QueryBuilder
     */
    protected $qb;

    /**
     * @var mixed
     */
    protected $rootEntityIdentifier;

    /**
     * @var array
     */
    protected $callbacks;

    /**
     * @var array
     */
    protected $response;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param array         $requestParams All GET params
     * @param ClassMetadata $metadata      A ClassMetadata instance
     * @param EntityManager $em            A EntityManager instance
     * @param Serializer    $serializer    A Serializer instance
     * @param Logger        $logger        A Logger instance
     */
    public function __construct($requestParams, ClassMetadata $metadata, EntityManager $em, Serializer $serializer, Logger $logger)
    {
        $this->requestParams  = $requestParams;
        $this->metadata       = $metadata;
        $this->em             = $em;
        $this->serializer     = $serializer;
        $this->logger         = $logger;

        $this->sEcho          = (int) $this->requestParams['sEcho'];
        $this->sSearch        = $this->requestParams['sSearch'];
        $this->iDisplayStart  = $this->requestParams['iDisplayStart'];
        $this->iDisplayLength = $this->requestParams['iDisplayLength'];
        $this->bRegex         = $this->requestParams['bRegex'];
        $this->iColumns       = $this->requestParams['iColumns'];
        $this->iSortingCols   = $this->requestParams['iSortingCols'];
        $this->iSortCol0      = $this->requestParams['iSortCol_0'];
        $this->sSortDir0      = $this->requestParams['sSortDir_0'];

        $this->tableName      = $metadata->getTableName();
        $this->selectFields   = array();
        $this->allFields      = array();
        $this->joins          = array();
        $this->qb             = $this->em->createQueryBuilder();

        $identifiers                = $this->metadata->getIdentifierFieldNames();
        $this->rootEntityIdentifier = array_shift($identifiers);

        $this->callbacks = array(
            'WhereBuilder' => array(),
            );

        $this->response = array();

        $this->prepare();
    }


    //-------------------------------------------------
    // Private
    //-------------------------------------------------

    /**
     * Show DQL in the Firebug / FirePHP console.
     *
     * @param string $msg
     */
    private function logDQL($msg)
    {
        $this->logger->info($msg . ' query:  ' . $this->qb->getQuery()->getDQL());

        $params = $this->qb->getQuery()->getParameters();

        foreach ($params as $param) {
            $this->logger->info($msg . ' param name:  ' . $param->getName() . ' param value: ' . $param->getValue());
        }
    }

    /**
     * Add a entry to the joins[] array.
     *
     * @param array $join
     *
     * @return DatatableData
     */
    private function addJoin(array $join)
    {
        $this->joins[] = $join;

        return $this;
    }

    /**
     * Set selectFields[], joins[] and allFields[] for associations.
     *
     * @param array         $fields   Association field array
     * @param int           $i        Numeric key
     * @param ClassMetadata $metadata A ClassMetadata instance
     *
     * @return DatatableData
     */
    private function setAssociations($fields, $i, $metadata)
    {
        $field = $fields[$i];

        if ($metadata->hasAssociation($field) === true) {
            $targetClass          = $metadata->getAssociationTargetClass($field);
            $targetMeta           = $this->em->getClassMetadata($targetClass);
            $targetTableName      = $targetMeta->getTableName();
            $targetIdentifiers    = $targetMeta->getIdentifierFieldNames();
            $targetRootIdentifier = array_shift($targetIdentifiers);

            if (!array_key_exists($targetTableName, $this->selectFields)) {
                $this->selectFields[$targetTableName][] = $targetRootIdentifier;

                $this->addJoin(
                    array(
                        'source' => $metadata->getTableName() . '.' . $field,
                        'target' => $targetTableName
                    )
                );
            }

            $i++;
            $this->setAssociations($fields, $i, $targetMeta);
        } else {
            $targetTableName      = $metadata->getTableName();
            $targetIdentifiers    = $metadata->getIdentifierFieldNames();
            $targetRootIdentifier = array_shift($targetIdentifiers);

            if ($field !== $targetRootIdentifier) {
                array_push($this->selectFields[$targetTableName], $field);
            }

            $this->allFields[] = $targetTableName . '.' . $field;
        }

        return $this;
    }

    /**
     * Prepare fields from mDataProp_ for createQueryBuilder.
     * Set selectFields[], joins[] and allFields[] arrays.
     *
     * @return DatatableData
     */
    private function prepare()
    {
        $this->selectFields[$this->tableName][] = $this->rootEntityIdentifier;

        $sColumns = explode(',', $this->requestParams['sColumns']);

        for ($i = 0; $i < $this->iColumns; $i++) {

            if ($this->requestParams['mDataProp_' . $i] != null) {

                $field = $this->requestParams['mDataProp_' . $i];

                if ($sColumns[$i] != '') {
                    if ($field != $sColumns[$i]) {
                        $field = $sColumns[$i];
                    }
                }

                // association delimiter found
                if (strstr($field, '.') !== false) {

                    // separate fields
                    $fieldsArray = explode('.', $field);
                    // set associations in selectFields[]
                    $this->setAssociations($fieldsArray, 0, $this->metadata);

                } else {

                    // no association found
                    if ($field !== $this->rootEntityIdentifier) {
                        array_push($this->selectFields[$this->tableName], $field);
                    }

                    $this->allFields[] = $this->tableName . '.' . $field;

                }

            } else {

                $this->allFields[] = '';

            }
        }

        return $this;
    }

    /**
     * Query results before filtering.
     *
     * @return int
     */
    private function getCountAllResults()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(' . $this->tableName . '.' . $this->rootEntityIdentifier . ')');
        $qb->from($this->metadata->getName(), $this->tableName);

        $this->setWhereCallbacks($qb);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Query results after filtering.
     *
     * @return int
     */
    private function getCountFilteredResults()
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(distinct ' . $this->tableName . '.' . $this->rootEntityIdentifier . ')');
        $qb->from($this->metadata->getName(), $this->tableName);

        $this->setLeftJoin($qb);
        $this->setWhere($qb);
        $this->setWhereCallbacks($qb);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Set select statement.
     *
     * @return DatatableData
     */
    private function setSelect()
    {
        foreach ($this->selectFields as $key => $value) {
            // example: $qb->select('partial comment.{id, title}, partial post.{id, title}');
            $this->qb->addSelect('partial ' . $key . '.{' . implode(',', $this->selectFields[$key]) . '}');
        }

        $this->qb->from($this->metadata->getName(), $this->tableName);

        return $this;
    }

    /**
     * Set leftJoin statement.
     *
     * @param QueryBuilder $qb A QueryBuilder instance
     *
     * @return DatatableData
     */
    private function setLeftJoin(QueryBuilder $qb)
    {
        foreach ($this->joins as $join) {
            $qb->leftJoin($join['source'], $join['target']);
        }

        return $this;
    }

    /**
     * Set where statement.
     *
     * @param QueryBuilder $qb A QueryBuilder instance
     *
     * @return DatatableData
     */
    private function setWhere(QueryBuilder $qb)
    {
        // global filtering
        if ($this->sSearch != '') {

            $orExpr = $qb->expr()->orX();

            for ($i = 0; $i < $this->iColumns; $i++) {
                if (isset($this->requestParams['bSearchable_' . $i]) && $this->requestParams['bSearchable_' . $i] === 'true') {
                    $searchField = $this->allFields[$i];
                    $orExpr->add($qb->expr()->like($searchField, "?$i"));
                    $qb->setParameter($i, "%" . $this->sSearch . "%");
                }
            }

            $qb->where($orExpr);
        }

        // individual filtering
        $andExpr = $qb->expr()->andX();

        for ($i = 0; $i < $this->iColumns; $i++) {
            if (isset($this->requestParams['bSearchable_' . $i]) && $this->requestParams['bSearchable_' . $i] === 'true' && $this->requestParams['sSearch_' . $i] != '') {
                $searchField = $this->allFields[$i];
                $andExpr->add($qb->expr()->like($searchField, "?$i"));
                $qb->setParameter($i, "%" . $this->requestParams['sSearch_' . $i] . "%");
            }
        }

        if ($andExpr->count() > 0) {
            $qb->andWhere($andExpr);
        }

        return $this;
    }

    /**
     * Set where callback functions.
     *
     * @param QueryBuilder $qb A QueryBuilder instance
     *
     * @return DatatableData
     */
    private function setWhereCallbacks(QueryBuilder $qb)
    {
        if (!empty($this->callbacks['WhereBuilder'])) {
            foreach ($this->callbacks['WhereBuilder'] as $callback) {
                $callback($qb);
            }
        }

        return $this;
    }

    /**
     * Set orderBy statement.
     *
     * @return DatatableData
     */
    private function setOrderBy()
    {
        if (isset($this->iSortCol0)) {
            for ($i = 0; $i < intval($this->requestParams['iSortingCols']); $i++) {
                if ($this->requestParams['bSortable_'.intval($this->requestParams['iSortCol_' . $i])] === 'true') {
                    $this->qb->addOrderBy(
                        $this->allFields[$this->requestParams['iSortCol_' . $i]],
                        $this->requestParams['sSortDir_' . $i]
                    );
                }
            }
        }

        return $this;
    }

    /**
     * Set the scope of the resultset (Paging).
     *
     * @return DatatableData
     */
    private function setLimit()
    {
        if (isset($this->iDisplayStart) && $this->iDisplayLength != '-1') {
            $this->qb->setFirstResult($this->iDisplayStart)->setMaxResults($this->iDisplayLength);
        }

        return $this;
    }

    /**
     * Set all statements.
     *
     * @return DatatableData
     */
    private function buildQuery()
    {
        $this->setSelect();
        $this->setLeftJoin($this->qb);
        $this->setWhere($this->qb);
        $this->setWhereCallbacks($this->qb);
        $this->setOrderBy();
        $this->setLimit();

        return $this;
    }

    /**
     * Execute query and build output array.
     *
     * @return DatatableData
     */
    private function executeQuery()
    {
        $query = $this->qb->getQuery();
        $query->setHydrationMode(Query::HYDRATE_ARRAY);
        $fresults = new Paginator($query, true);

        $output = array("aaData" => array());

        foreach ($fresults as $item) {
            $output['aaData'][] = $item;
        }

        $outputHeader = array(
            "sEcho" => (int) $this->sEcho,
            "iTotalRecords" => $this->getCountAllResults(),
            "iTotalDisplayRecords" => $this->getCountFilteredResults()
        );

        $this->response = array_merge($outputHeader, $output);

        return $this;
    }


    //-------------------------------------------------
    // DatatableDataInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getSearchResults()
    {
        $this->buildQuery();
        $this->executeQuery();

        $json = $this->serializer->serialize($this->response, 'json');
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }


    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * Add callback function.
     *
     * @param string $callback
     *
     * @return DatatableData
     * @throws \Exception
     */
    public function addWhereBuilderCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new \Exception("The callback argument must be callable.");
        }

        $this->callbacks['WhereBuilder'][] = $callback;

        return $this;
    }
}
