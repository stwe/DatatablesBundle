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
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;

/**
 * Class DatatableQuery
 *
 * A thanks goes to the authors of the original implementation:
 *     Félix-Antoine Paradis (https://gist.github.com/reel/1638094) and
 *     Chad Sikorra (https://github.com/LanKit/DatatablesBundle)
 *
 * @package Sg\DatatablesBundle\Datatable
 */
class DatatableQuery
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * The name of the primary table.
     *
     * @var string
     */
    protected $tableName;

    /**
     * The name of the entity class.
     *
     * @var string
     */
    protected $entityName;

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
    protected $allColumns;

    /**
     * @var array
     */
    protected $joins;

    /**
     * @var array
     */
    protected $requestParams;

    /**
     * @var array
     */
    protected $callbacks;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param EntityManager $em            A EntityManager instance
     * @param string        $tableName     The name of the primary table
     * @param string        $entityName    The name of the entity class
     * @param array         $requestParams All GET params
     */
    public function __construct(EntityManager $em, $tableName, $entityName, array $requestParams)
    {
        $this->em            = $em;
        $this->tableName     = $tableName;
        $this->entityName    = $entityName;
        $this->qb            = $this->em->createQueryBuilder();
        $this->selectColumns = array();
        $this->allColumns    = array();
        $this->joins         = array();
        $this->requestParams = $requestParams;
        $this->callbacks     = array(
            'WhereBuilder' => array(),
        );
    }


    //-------------------------------------------------
    // Public
    //-------------------------------------------------

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
     * Set selectColumns.
     *
     * @param array $selectColumns
     *
     * @return $this
     */
    public function setSelectColumns(array $selectColumns)
    {
        $this->selectColumns = $selectColumns;

        return $this;
    }

    /**
     * Set allColumns.
     *
     * @param array $allColumns
     *
     * @return $this
     */
    public function setAllColumns(array $allColumns)
    {
        $this->allColumns = $allColumns;

        return $this;
    }

    /**
     * Set joins.
     *
     * @param array $joins
     *
     * @return $this
     */
    public function setJoins(array $joins)
    {
        $this->joins = $joins;

        return $this;
    }

    /**
     * Add callback.
     *
     * @param string $callback
     *
     * @return $this
     */
    public function addCallback($callback)
    {
        $this->callbacks['WhereBuilder'][] = $callback;

        return $this;
    }

    /**
     * Query results before filtering.
     *
     * @param integer $rootEntityIdentifier
     *
     * @return int
     */
    public function getCountAllResults($rootEntityIdentifier)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(' . $this->tableName . '.' . $rootEntityIdentifier . ')');
        $qb->from($this->entityName, $this->tableName);

        $this->setWhereCallbacks($qb);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Query results after filtering.
     *
     * @param integer $rootEntityIdentifier
     *
     * @return int
     */
    public function getCountFilteredResults($rootEntityIdentifier)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('count(distinct ' . $this->tableName . '.' . $rootEntityIdentifier . ')');
        $qb->from($this->entityName, $this->tableName);

        $this->setLeftJoins($qb);
        $this->setWhere($qb);
        $this->setWhereCallbacks($qb);

        return (int) $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Set select from.
     *
     * @return $this
     */
    public function setSelectFrom()
    {
        foreach ($this->selectColumns as $key => $value) {
            // $qb->select('partial comment.{id, title}, partial post.{id, title}');
            $this->qb->addSelect('partial ' . $key . '.{' . implode(',', $this->selectColumns[$key]) . '}');
        }

        $this->qb->from($this->entityName, $this->tableName);

        return $this;
    }

    /**
     * Set leftJoins.
     *
     * @param QueryBuilder $qb A QueryBuilder instance
     *
     * @return $this
     */
    public function setLeftJoins(QueryBuilder $qb)
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
     * @return $this
     */
    public function setWhere(QueryBuilder $qb)
    {
        // global filtering
        if ($this->requestParams['sSearch'] != '') {

            $orExpr = $qb->expr()->orX();

            for ($i = 0; $i < $this->requestParams['iColumns']; $i++) {
                if (isset($this->requestParams['bSearchable_' . $i]) && $this->requestParams['bSearchable_' . $i] === 'true') {
                    $searchField = $this->allColumns[$i];
                    $orExpr->add($qb->expr()->like($searchField, "?$i"));
                    $qb->setParameter($i, "%" . $this->requestParams['sSearch'] . "%");
                }
            }

            $qb->where($orExpr);
        }

        // individual filtering
        $andExpr = $qb->expr()->andX();

        for ($i = 0; $i < $this->requestParams['iColumns']; $i++) {
            if (isset($this->requestParams['bSearchable_' . $i]) && $this->requestParams['bSearchable_' . $i] === 'true' && $this->requestParams['sSearch_' . $i] != '') {
                $searchField = $this->allColumns[$i];
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
     * @return $this
     */
    public function setWhereCallbacks(QueryBuilder $qb)
    {
        if (!empty($this->callbacks['WhereBuilder'])) {
            foreach ($this->callbacks['WhereBuilder'] as $callback) {
                $callback($qb);
            }
        }

        return $this;
    }

    /**
     * Set orderBy.
     *
     * @return $this
     */
    public function setOrderBy()
    {
        if (isset($this->requestParams['iSortCol_0'])) {
            for ($i = 0; $i < intval($this->requestParams['iSortingCols']); $i++) {
                if ($this->requestParams['bSortable_'.intval($this->requestParams['iSortCol_' . $i])] === 'true') {
                    $this->qb->addOrderBy(
                        $this->allColumns[$this->requestParams['iSortCol_' . $i]],
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
     * @return $this
     */
    public function setLimit()
    {
        if (isset($this->requestParams['iDisplayStart']) && $this->requestParams['iDisplayLength'] != '-1') {
            $this->qb->setFirstResult($this->requestParams['iDisplayStart'])->setMaxResults($this->requestParams['iDisplayLength']);
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
        $query->setHydrationMode(Query::HYDRATE_ARRAY);

        return $query;
    }
}