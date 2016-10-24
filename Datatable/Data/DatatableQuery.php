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
     * @var Paginator
     */
    private $paginator;

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
        $this->callbacks = array();
        $this->columns = $datatableView->getColumnBuilder()->getColumns();
        $this->paginator = null;

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
     * @author greg-avanim <https://github.com/greg-avanim>
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

            $currentPart = $this->tableName;
            $currentAlias = $currentPart;

            $metadata = $this->metadata;

            if (true === $this->isSelectColumn($data)) {
                $parts = explode('\\\\.', $data);

                if (count($parts) > 1) {
                    // If it's an embedded class, we can query without JOIN
                    if (array_key_exists($parts[0], $metadata->embeddedClasses)) {
                        $column = str_replace('\\', '', $data);
                        $this->selectColumns[$currentAlias][] = $column;
                        $this->addSearchOrderColumn($key, $currentAlias, $column);
                        continue;
                    }
                } else {
                    $parts = explode('.', $data);

                    while (count($parts) > 1) {
                        $previousPart = $currentPart;
                        $previousAlias = $currentAlias;

                        $currentPart = array_shift($parts);
                        $currentAlias = ($previousPart == $this->tableName ? '' : $previousPart.'_') . $currentPart; // This condition keeps stable queries callbacks

                        if (!array_key_exists($previousAlias.'.'.$currentPart, $this->joins)) {
                            $this->joins[$previousAlias.'.'.$currentPart] = $currentAlias;
                        }

                        $metadata = $this->setIdentifierFromAssociation($currentAlias, $currentPart, $metadata);
                    }

                    $this->selectColumns[$currentAlias][] = $this->getIdentifier($metadata);
                    $this->selectColumns[$currentAlias][] = $parts[0];
                    $this->addSearchOrderColumn($key, $currentAlias, $parts[0]);
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
    public function buildQuery()
    {
        $this->setSelectFrom();
        $this->setLeftJoins($this->qb);
        $this->setWhere($this->qb);
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
     * Add the where-all function.
     *
     * @param $callback
     *
     * @return $this
     * @throws Exception
     */
    public function addWhereAll($callback)
    {
        if (!is_callable($callback)) {
            throw new Exception(sprintf("Callable expected and %s given", gettype($callback)));
        }

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

    /**
     * Add response callback.
     *
     * @param $callback
     *
     * @return $this
     * @throws Exception
     */
    public function addResponseCallback($callback)
    {
        if (!is_callable($callback)) {
            throw new Exception(sprintf("Callable expected and %s given", gettype($callback)));
        }

        $this->callbacks['Response'][] = $callback;

        return $this;
    }

    /**
     * Apply response callbacks.
     *
     * @param array $data
     *
     * @return array
     */
    private function applyResponseCallbacks(array $data)
    {
        if (!empty($this->callbacks['Response'])) {
            foreach ($this->callbacks['Response'] as $callback) {
                $data = $callback($data, $this);
            }
        }

        return $data;
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
                    
                    if (array_key_exists($key, $this->requestParams['columns']) === false) {
                        continue;
                    }
                    
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
     * @param bool $outputWalkers
     *
     * @return Response
     * @throws Exception
     */
    public function getResponse($buildQuery = true, $outputWalkers = false)
    {
        false === $buildQuery ? : $this->buildQuery();

        $this->paginator = new Paginator($this->execute(), true);
        $this->paginator->setUseOutputWalkers($outputWalkers);

        $formatter = new DatatableFormatter($this);
        $formatter->runFormatter();

        $countAllResults = $this->datatableView->getOptions()->getCountAllResults();

        $outputHeader = array(
            'draw' => (int) $this->requestParams['draw'],
            'recordsTotal' => true === $countAllResults ? (int) $this->getCountAllResults($this->rootEntityIdentifier) : 0,
            'recordsFiltered' => (int) $this->getCountFilteredResults($this->rootEntityIdentifier, $buildQuery)
        );

        $fullOutput = array_merge($outputHeader, $formatter->getOutput());
        $fullOutput = $this->applyResponseCallbacks($fullOutput);

        $json = $this->serializer->serialize($fullOutput, 'json');
        $response = new Response($json);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * Simple function to get results for export to PHPExcel.
     *
     * @return array
     * @throws Exception
     */
    public function getDataForExport()
    {
        $this->setSelectFrom();
        $this->setLeftJoins($this->qb);
        $this->setWhereAllCallback($this->qb);
        $this->setOrderBy();

        return $this->execute()->getArrayResult();
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
     * Get lineFormatter.
     *
     * @return callable
     */
    public function getLineFormatter()
    {
        return $this->lineFormatter;
    }

    /**
     * Get columns.
     *
     * @return AbstractColumn[]
     */
    public function getColumns()
    {
        return $this->columns;
    }

    /**
     * Get paginator.
     *
     * @return Paginator|null
     */
    public function getPaginator()
    {
        return $this->paginator;
    }

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
