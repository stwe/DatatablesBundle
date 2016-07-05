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
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Exception;
use Twig_Environment;

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
    private $serializer;

    /**
     * @var array
     */
    private $requestParams;

    /**
     * @var DatatableViewInterface
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
    private $selects;

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
    private $configs;

    /**
     * @var Twig_Environment
     */
    private $twig;

    /**
     * @var boolean
     */
    private $imagineBundle;

    /**
     * @var boolean
     */
    private $doctrineExtensions;

    /**
     * @var string
     */
    private $locale;

    /**
     * @var boolean
     */
    private $isPostgreSQLConnection;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param Serializer             $serializer
     * @param array                  $requestParams
     * @param DatatableViewInterface $datatableView
     * @param array                  $configs
     * @param Twig_Environment       $twig
     * @param boolean                $imagineBundle
     * @param boolean                $doctrineExtensions
     * @param string                 $locale
     *
     * @throws Exception
     */
    public function __construct(
        Serializer $serializer,
        array $requestParams,
        DatatableViewInterface $datatableView,
        array $configs,
        Twig_Environment $twig,
        $imagineBundle,
        $doctrineExtensions,
        $locale
    )
    {
        $this->serializer = $serializer;
        $this->requestParams = $requestParams;
        $this->datatableView = $datatableView;

        $this->individualFiltering = $this->datatableView->getOptions()->getIndividualFiltering();

        $this->entity = $this->datatableView->getEntity();
        $this->em = $this->datatableView->getEntityManager();
        $this->metadata = $this->getMetadata($this->entity);
        $this->tableName = $this->getTableName($this->metadata);
        $this->rootEntityIdentifier = $this->getIdentifier($this->metadata);
        $this->qb = $this->em->createQueryBuilder();

        $this->selectColumns = array();
        $this->virtualColumns = $datatableView->getColumnBuilder()->getVirtualColumns();
        $this->joins = array();
        $this->searchColumns = array();
        $this->orderColumns = array();
        $this->selects = array();
        $this->callbacks = array();
        $this->columns = $datatableView->getColumnBuilder()->getColumns();

        $this->configs = $configs;

        $this->twig = $twig;
        $this->imagineBundle = $imagineBundle;
        $this->doctrineExtensions = $doctrineExtensions;
        $this->locale = $locale;
        $this->isPostgreSQLConnection = false;

        $this->setLineFormatter();
        $this->setupColumnArrays();

        $this->setupPostgreSQL();
    }

    //-------------------------------------------------
    // PostgreSQL
    //-------------------------------------------------

    /**
     * Setup PostgreSQL
     *
     * @return $this
     * @throws \Doctrine\ORM\ORMException
     */
    private function setupPostgreSQL()
    {
        if ($this->em->getConnection()->getDriver()->getName() === 'pdo_pgsql') {
            $this->isPostgreSQLConnection = true;
            $this->em->getConfiguration()->addCustomStringFunction('CAST', '\Sg\DatatablesBundle\DQL\CastFunction');
        }

        return $this;
    }

    /**
     * Cast search field.
     *
     * @param string         $searchField
     * @param AbstractColumn $column
     *
     * @return string
     */
    private function cast($searchField, AbstractColumn $column)
    {
        if ('datetime' === $column->getAlias() || 'boolean' === $column->getAlias() || 'column' === $column->getAlias()) {
            return 'CAST('.$searchField.' AS text)';
        }

        return $searchField;
    }

    //-------------------------------------------------
    // Setup query
    //-------------------------------------------------

    /**
     * Setup column arrays.
     *
     * @author stwe <https://github.com/stwe>
     * @author Gaultier Boniface <https://github.com/wysow>
     *
     * @return $this
     */
    private function setupColumnArrays()
    {
        /* Example:
              SELECT
                  partial fos_user.{id},
                  partial posts_comments.{title,id},
                  partial posts.{id,title}
              FROM
                  AppBundle\Entity\User fos_user
              LEFT JOIN
                  fos_user.posts posts
              LEFT JOIN
                  posts.comments posts_comments
              ORDER BY
                  posts_comments.title asc
         */

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
                    $replaced = str_replace('.', '_', $data);
                    $parts = explode('_', $replaced);
                    $last = array_pop($parts);

                    $previousPart = $this->tableName;
                    $currentPart = null;
                    $metadata = null;
                    $select = null;

                    while (count($parts) > 0) {
                        $select = implode('_', $parts);

                        if (in_array($select, $this->selects)) {
                            $select .= '_'.uniqid();
                        }

                        $this->selects[] = $select;
                        $currentPart = array_shift($parts);

                        if (!array_key_exists($previousPart.'.'.$currentPart, $this->joins)) {
                            $this->joins[$previousPart.'.'.$currentPart] = $select;
                        } else {
                            $select = $this->joins[$previousPart.'.'.$currentPart];
                        }

                        if (false === array_key_exists($select, $this->selectColumns)) {
                            $metadata = $this->setIdentifierFromAssociation($select, $currentPart, $metadata);
                        }

                        $previousPart = $select;
                    }

                    $this->selectColumns[$select][] = $last;
                    $this->addSearchOrderColumn($key, $select, $last);
                }
            } else {
                $this->orderColumns[] = null;
                $this->searchColumns[] = null;
            }

        }

        return $this;
    }

    /**
     * Build query.
     *
     * @return $this
     */
    public function buildQuery($limit = true)
    {
        $this->setSelectFrom();
        $this->setLeftJoins($this->qb);
        $this->setWhere($this->qb);
        $this->setWhereResultCallback($this->qb);
        $this->setWhereAllCallback($this->qb);
        $this->setOrderBy();

        if ($limit) {
            $this->setLimit();
        }

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
     * @param $callback
     *
     * @return $this
     */
    public function addWhereResult($callback)
    {
        $this->callbacks['WhereResult'][] = $callback;

        return $this;
    }

    /**
     * Add the where-all function.
     *
     * @param $callback
     *
     * @return $this
     */
    public function addWhereAll($callback)
    {
        $this->callbacks['WhereAll'][] = $callback;

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
            foreach ($this->callbacks['WhereResult'] as $callback) {
                $callback($qb);
            }
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
            foreach ($this->callbacks['WhereAll'] as $callback) {
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
            $this->qb->addSelect('partial ' . $key . '.{' . implode(',', $this->selectColumns[$key]) . '}');
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

                    if (true === $this->isPostgreSQLConnection) {
                        $searchField = $this->cast($searchField, $column);
                    }

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
                    $filter = $column->getFilter();
                    $searchField = $this->searchColumns[$key];
                    $searchValue = $this->requestParams['columns'][$key]['search']['value'];
                    if ('' != $searchValue && 'null' != $searchValue) {
                        if (true === $this->isPostgreSQLConnection) {
                            $searchField = $this->cast($searchField, $column);
                        }

                        $andExpr = $filter->addAndExpression($andExpr, $qb, $searchField, $searchValue, $i);
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
     * @param bool    $buildQuery
     *
     * @return int
     */
    private function getCountFilteredResults($rootEntityIdentifier, $buildQuery = true)
    {
        if (true === $buildQuery) {
            $qb = $this->em->createQueryBuilder();
            $qb->select('count(distinct ' . $this->tableName . '.' . $rootEntityIdentifier . ')');
            $qb->from($this->entity, $this->tableName);

            $this->setLeftJoins($qb);
            $this->setWhere($qb);
            $this->setWhereAllCallback($qb);

            return (int) $qb->getQuery()->getSingleScalarResult();
        } else {
            $this
                ->qb
                ->setFirstResult(null)
                ->setMaxResults(null)
                ->select('count(distinct ' . $this->tableName . '.' . $rootEntityIdentifier . ')');
            if (true === $this->isPostgreSQLConnection) {
                $this->qb->groupBy($this->tableName . '.' . $rootEntityIdentifier);
                return count($this->qb->getQuery()->getResult());
            } else {
                return (int) $this->qb->getQuery()->getSingleScalarResult();
            }
        }
    }

    /**
     * Constructs a Query instance.
     *
     * @return Query
     * @throws Exception
     */
    private function execute()
    {
        $query = $this->qb->getQuery();

        if (true === $this->configs['translation_query_hints']) {
            if (true === $this->doctrineExtensions) {
                $query->setHint(
                    \Doctrine\ORM\Query::HINT_CUSTOM_OUTPUT_WALKER,
                    'Gedmo\\Translatable\\Query\\TreeWalker\\TranslationWalker'
                );

                $query->setHint(
                    \Gedmo\Translatable\TranslatableListener::HINT_TRANSLATABLE_LOCALE,
                    $this->locale
                );

                $query->setHint(
                    \Gedmo\Translatable\TranslatableListener::HINT_FALLBACK,
                    1
                );
            } else {
                throw new Exception('execute(): "DoctrineExtensions" does not exist.');
            }
        }

        $query->setHydrationMode(Query::HYDRATE_ARRAY);

        return $query;
    }

    //-------------------------------------------------
    // Response
    //-------------------------------------------------

    /**
     * Get response.
     *
     * @param bool $buildQuery
     *
     * @return Response
     * @throws Exception
     */
    public function getResponse($buildQuery = true, $limit = true)
    {
        false === $buildQuery ? : $this->buildQuery($limit);

        $fresults = new Paginator($this->execute(), true);
        $fresults->setUseOutputWalkers(false);
        $output = array('data' => array());

        foreach ($fresults as $item) {
            if (is_callable($this->lineFormatter)) {
                $callable = $this->lineFormatter;
                $item = call_user_func($callable, $item);
            }

            foreach ($this->columns as $column) {
                $column->renderContent($item, $this);

                /** @var ActionColumn $column */
                if ('action' === $column->getAlias()) {
                    $column->checkVisibility($item);
                }
            }

            $output['data'][] = $item;
        }

        $outputHeader = array(
            'draw' => (int) $this->requestParams['draw'],
            'recordsTotal' => (int) $this->getCountAllResults($this->rootEntityIdentifier)
        );

        if ($this->getQuery()->getDQLPart('where') === null) {
            $outputHeader['recordsFiltered'] = $outputHeader['recordsTotal'];
        } else {
            $outputHeader['recordsFiltered'] = (int) $this->getCountFilteredResults($this->rootEntityIdentifier, $buildQuery);
        }

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
        $this->selectColumns[$association][] = $this->getIdentifier($targetMetadata);

        return $targetMetadata;
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
        if (false === $this->configs['search_on_non_visible_columns']) {
            if (null !== $column->getDql() && true === $column->getSearchable() && true === $column->getVisible()) {
                return true;
            }
        } else {
            if (null !== $column->getDql() && true === $column->getSearchable()) {
                return true;
            }
        }

        return false;
    }

    //-------------------------------------------------
    // Getters
    //-------------------------------------------------

    /**
     * Get Twig Environment.
     *
     * @return Twig_Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }

    /**
     * Get imagineBundle.
     *
     * @return boolean
     */
    public function getImagineBundle()
    {
        return $this->imagineBundle;
    }
}
