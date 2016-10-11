<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Tests;

/**
 * Class DatatableTest
 *
 * @package Sg\DatatablesBundle\Tests
 */
class DatatableTest extends BaseDatatablesTestCase
{
    public function testCreate()
    {
        $table = $this->createDummyDatatable();

        $this->assertEquals('post_datatable', $table->getName());

        $table->buildDatatable();
    }

    public function testCreateDatatableChecksOptions()
    {
        $table = $this->createDummyDatatable();
        $table->buildDatatable();

        $this->assertNotEmpty($table->getTopActions());
        $this->assertNotEmpty($table->getFeatures());
        $this->assertNotEmpty($table->getAjax());
        $this->assertNotEmpty($table->getOptions());
    }

    public function testCreateDatatableCheckColumns()
    {
        $table = $this->createDummyDatatable();
        $table->buildDatatable();

        /**
         * @var \Sg\DatatablesBundle\Datatable\Column\AbstractColumn[] $columns
         */
        $columns = $table->getColumnBuilder()->getColumns();

        $this->assertNotEmpty($columns);
        $this->assertCount(2, $columns);

        $columnOneOptions = $this->getPrivateProperty($columns[0], 'options');

        $this->assertEquals('id', $columns[0]->getData());
        $this->assertEquals('Id', $columnOneOptions['title']);

        $columnTwoOptions = $this->getPrivateProperty($columns[1], 'options');

        $this->assertEquals('title', $columns[1]->getData());
        $this->assertEquals('Title', $columnTwoOptions['title']);
    }
}
