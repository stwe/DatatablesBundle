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
            'search' => array(
                'value' => '',
            ),
            'order' => array(),
            'columns' => array(),
        );

        $datatable = $this->createDummyDatatable();
        $datatable->buildDatatable();

        $configs = array();
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


    public function testDummy()
    {
        $this->assertTrue(true);
    }


    public function testSetOrderByEmptyRequestParams()
    {
        $this->datatableQuery->buildQuery();

        $dqlParts = $this->datatableQuery->getQuery()->getDQLParts();

        $this->assertInternalType('array', $dqlParts);
        $this->assertEmpty($dqlParts['orderBy']);
    }

    public function testSetOrderByWithOrderableColumns()
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

    public function testSetOrderByWithOrderableColumnsContaingNumbersInStrings()
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
}
