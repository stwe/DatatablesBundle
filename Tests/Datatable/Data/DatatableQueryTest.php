<?php

namespace Sg\DatatablesBundle\Tests\Datatable\Data;

use Sg\DatatablesBundle\Datatable\Data\DatatableQuery;
use Sg\DatatablesBundle\Tests\BaseDatatablesTestCase;

/**
 * Class DatatableQueryTest
 * @package Sg\DatatablesBundle\Tests\Datatable\Data
 */
class DatatableQueryTest extends BaseDatatablesTestCase
{
    /**
     * @var DatatableQuery
     */
    protected $datatableQuery;


    protected function setUp()
    {
        $serializer = $this->getMockBuilder('Symfony\Component\Serializer\Serializer')->getMock();
        $requestParams = array(
            'search'  => array(
                'value' => '',
            ),
            'order'   => array(),
            'columns' => array(),
        );

        $datatable = $this->createDummyDatatable();
        $datatable->buildDatatable();

        $configs = array(
            'search_on_non_visible_columns' => false,
            'translation_query_hints'       => false,
        );
        $twig = $this->getMockBuilder('Twig_Environment')->getMock();
        $imagineBundle = false;
        $doctrineExtensions = false;
        $locale = 'en';

        foreach ($datatable->getColumnBuilder()->getColumns() as $column) {
            $requestParams['columns'][] = $this->getPrivateProperty($column, 'options');
        }

        $this->datatableQuery = new DatatableQuery(
            $serializer,
            $requestParams,
            $datatable,
            $configs,
            $twig,
            $imagineBundle,
            $doctrineExtensions,
            $locale
        );
    }


    public function testBuildQuery()
    {
        $this->datatableQuery->buildQuery();

        $dqlParts = $this->datatableQuery->getQuery()->getDQLParts();

        $this->assertInternalType('array', $dqlParts);
        $this->assertNotEmpty($dqlParts);
    }

    public function testBuildQueryCheckFrom()
    {
        $this->datatableQuery->buildQuery();
        /** @var \Sg\DatatablesBundle\Datatable\View\AbstractDatatableView $datatableView */
        $datatableView = $this->getPrivateProperty($this->datatableQuery, 'datatableView');

        $dqlParts = $this->datatableQuery->getQuery()->getDQLParts();

        // Check "FROM"
        $this->assertNotEmpty($dqlParts['from']);

        /** @var \Doctrine\ORM\Query\Expr\From $fromPart */
        $fromPart = $dqlParts['from'][0];

        $this->assertNotEmpty($fromPart->getFrom());
        $this->assertContains($datatableView->getEntity(), $fromPart->getFrom());
    }

    public function testBuildQueryCheckSelect()
    {
        $this->datatableQuery->buildQuery();

        $dqlParts = $this->datatableQuery->getQuery()->getDQLParts();

        // Check "SELECT"
        $this->assertNotEmpty($dqlParts['select']);

        /** @var \Doctrine\ORM\Query\Expr\Select $selectPart */
        $selectPart = $dqlParts['select'][0];
        $selectPartFirst = current($selectPart->getParts());

        $this->assertNotEmpty($selectPart->getParts());
        $this->assertCount(1, $selectPart->getParts());
        $this->assertContains('partial', $selectPartFirst);
        $this->assertContains('id', $selectPartFirst);
        $this->assertContains('title', $selectPartFirst);
    }

    public function testBuildQueryCheckWhereWithGlobalSearch()
    {
        // Fake a global search
        $extendedRequestParams = array(
            'search' => array(
                'value' => 'test',
            ),
        );
        $this->extendPrivateArrayProperty($this->datatableQuery, 'requestParams', $extendedRequestParams);

        $this->datatableQuery->buildQuery();

        $dqlParts = $this->datatableQuery->getQuery()->getDQLParts();

        // Check "WHERE"
        $this->assertNotEmpty($dqlParts['where']);
        $this->assertInstanceOf('\Doctrine\ORM\Query\Expr\Orx', $dqlParts['where']);

        $whereParts = $dqlParts['where']->getParts();
        $this->assertNotEmpty($whereParts);

        /** @var \Doctrine\ORM\Query\Expr\Comparison $wherePartOne */
        $wherePartOne = $whereParts[0];
        $this->assertNotEmpty($wherePartOne);
        $this->assertContains('id', $wherePartOne->getLeftExpr());

        /** @var \Doctrine\ORM\Query\Expr\Comparison $wherePartTwo */
        $wherePartTwo = $whereParts[1];
        $this->assertNotEmpty($wherePartTwo);
        $this->assertContains('title', $wherePartTwo->getLeftExpr());
    }

    public function testBuildQueryCheckWhereWithIndividualSearch()
    {
        // Fake a global search
        $extendedRequestParams = array(
            'search'  => array(
                'value' => '',
            ),
            'columns' => array(
                // Column "Id"
                array('search' => array('value' => 'foo')),
                // Column "Title"
                array('search' => array('value' => 'bar')),
            ),
        );
        $this->extendPrivateArrayProperty($this->datatableQuery, 'requestParams', $extendedRequestParams);
        $this->setPrivateProperty($this->datatableQuery, 'individualFiltering', true);

        $this->datatableQuery->buildQuery();

        $dqlParts = $this->datatableQuery->getQuery()->getDQLParts();

        // Check "WHERE"
        $this->assertNotEmpty($dqlParts['where']);
        $this->assertInstanceOf('\Doctrine\ORM\Query\Expr\Andx', $dqlParts['where']);

        $whereParts = current($dqlParts['where']->getParts())->getParts();
        $this->assertNotEmpty($whereParts);

        /** @var \Doctrine\ORM\Query\Expr\Comparison $wherePartOne */
        $wherePartOne = $whereParts[0];
        $this->assertNotEmpty($wherePartOne);
        $this->assertContains('id', $wherePartOne->getLeftExpr());

        /** @var \Doctrine\ORM\Query\Expr\Comparison $wherePartTwo */
        $wherePartTwo = $whereParts[1];
        $this->assertNotEmpty($wherePartTwo);
        $this->assertContains('title', $wherePartTwo->getLeftExpr());
    }

    public function testBuildQueryCheckWhereWithIndividualSearchWithEmptySearchValue()
    {
        // Fake a global search
        $extendedRequestParams = array(
            'search'  => array(
                'value' => '',
            ),
            'columns' => array(
                // Column "Id"
                array('search' => array('value' => 'foo')),
                // Column "Title"
                array('search' => array('value' => '')),
            ),
        );
        $this->extendPrivateArrayProperty($this->datatableQuery, 'requestParams', $extendedRequestParams);
        $this->setPrivateProperty($this->datatableQuery, 'individualFiltering', true);

        $this->datatableQuery->buildQuery();

        $dqlParts = $this->datatableQuery->getQuery()->getDQLParts();

        // Check "WHERE"
        $this->assertNotEmpty($dqlParts['where']);
        $this->assertInstanceOf('\Doctrine\ORM\Query\Expr\Andx', $dqlParts['where']);

        $whereParts = current($dqlParts['where']->getParts())->getParts();
        $this->assertNotEmpty($whereParts);

        /** @var \Doctrine\ORM\Query\Expr\Comparison $wherePartOne */
        $wherePartOne = $whereParts[0];
        $this->assertNotEmpty($wherePartOne);
        $this->assertContains('id', $wherePartOne->getLeftExpr());

        $this->assertArrayNotHasKey(1, $whereParts);
    }

    public function testBuildQueryCheckWhereWithIndividualSearchWithNullAsSearchValue()
    {
        // Fake a global search
        $extendedRequestParams = array(
            'search'  => array(
                'value' => '',
            ),
            'columns' => array(
                // Column "Id"
                array('search' => array('value' => 'foo')),
                // Column "Title"
                array('search' => array('value' => 'null')),
            ),
        );
        $this->extendPrivateArrayProperty($this->datatableQuery, 'requestParams', $extendedRequestParams);
        $this->setPrivateProperty($this->datatableQuery, 'individualFiltering', true);

        $this->datatableQuery->buildQuery();

        $dqlParts = $this->datatableQuery->getQuery()->getDQLParts();

        // Check "WHERE"
        $this->assertNotEmpty($dqlParts['where']);
        $this->assertInstanceOf('\Doctrine\ORM\Query\Expr\Andx', $dqlParts['where']);

        $whereParts = current($dqlParts['where']->getParts())->getParts();
        $this->assertNotEmpty($whereParts);

        /** @var \Doctrine\ORM\Query\Expr\Comparison $wherePartOne */
        $wherePartOne = $whereParts[0];
        $this->assertNotEmpty($wherePartOne);
        $this->assertContains('id', $wherePartOne->getLeftExpr());

        $this->assertArrayNotHasKey(1, $whereParts);
    }

    public function testBuildQueryCheckOrderByWithEmptyRequestParams()
    {
        $this->datatableQuery->buildQuery();

        $dqlParts = $this->datatableQuery->getQuery()->getDQLParts();

        $this->assertInternalType('array', $dqlParts);
        $this->assertEmpty($dqlParts['orderBy']);
    }

    public function testBuildQueryCheckOrderByWithOrderableColumns()
    {
        $extendedRequestParams = array(
            'order' => array(
                array('column' => 0, 'dir' => 'asc'),
                array('column' => 1, 'dir' => 'desc'),
            ),
        );
        $this->extendPrivateArrayProperty($this->datatableQuery, 'requestParams', $extendedRequestParams);

        $this->datatableQuery->buildQuery();

        $dqlParts = $this->datatableQuery->getQuery()->getDQLParts();

        $this->assertInternalType('array', $dqlParts);
        $this->assertNotEmpty($dqlParts['orderBy']);

        $orderByPartOne = $this->getPrivateProperty($dqlParts['orderBy'][0], 'parts');
        $this->assertContains('id asc', $orderByPartOne[0]);

        $orderByPartTwo = $this->getPrivateProperty($dqlParts['orderBy'][1], 'parts');
        $this->assertContains('title desc', $orderByPartTwo[0]);
    }

    public function testBuildQueryCheckOrderByWithOrderableColumnsContaingNumbersInStrings()
    {
        $extendedRequestParams = array(
            'order' => array(
                array('column' => 0, 'dir' => 'asc'),
                array('column' => 1, 'dir' => 'desc'),
            ),
        );
        $this->extendPrivateArrayProperty($this->datatableQuery, 'requestParams', $extendedRequestParams);

        // Set columns of type number/integer
        $extendedColumns = $this->getPrivateProperty($this->datatableQuery, 'columns');
        $extendedColumns[0]->setType('num');
        $extendedColumns[1]->setType('int');
        $this->setPrivateProperty($this->datatableQuery, 'columns', $extendedColumns);

        $this->datatableQuery->buildQuery();

        $dqlParts = $this->datatableQuery->getQuery()->getDQLParts();

        $this->assertInternalType('array', $dqlParts);
        $this->assertNotEmpty($dqlParts['orderBy']);

        $orderByPartOne = $this->getPrivateProperty($dqlParts['orderBy'][0], 'parts');
        $this->assertContains('id_order_as_int asc', $orderByPartOne[0]);

        $orderByPartTwo = $this->getPrivateProperty($dqlParts['orderBy'][1], 'parts');
        $this->assertContains('title_order_as_int desc', $orderByPartTwo[0]);
    }

    public function testBuildQueryCheckLimitWithNoLimitGiven()
    {
        $this->datatableQuery->buildQuery();

        // Check "LIMIT"
        $qb = $this->datatableQuery->getQuery();

        $this->assertNull($qb->getFirstResult());
        $this->assertNull($qb->getMaxResults());
    }

    public function testBuildQueryCheckLimitWithInvalidLength()
    {
        $extendedRequestParams = array(
            'start'  => 10,
            'length' => -1,
        );
        $this->extendPrivateArrayProperty($this->datatableQuery, 'requestParams', $extendedRequestParams);

        $this->datatableQuery->buildQuery();

        // Check "LIMIT"
        $qb = $this->datatableQuery->getQuery();

        $this->assertNull($qb->getFirstResult());
        $this->assertNull($qb->getMaxResults());
    }

    public function testBuildQueryCheckLimit()
    {
        $extendedRequestParams = array(
            'start'  => 10,
            'length' => 20,
        );
        $this->extendPrivateArrayProperty($this->datatableQuery, 'requestParams', $extendedRequestParams);

        $this->datatableQuery->buildQuery();

        // Check "LIMIT"
        $qb = $this->datatableQuery->getQuery();

        $this->assertEquals(10, $qb->getFirstResult());
        $this->assertEquals(20, $qb->getMaxResults());
    }
}
