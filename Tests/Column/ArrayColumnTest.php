<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace Sg\DatatablesBundle\Tests\Column;

use Sg\DatatablesBundle\Datatable\Column\ArrayColumn;

/**
 * Class ArrayColumnTest
 * @package Sg\DatatablesBundle\Tests\Column
 */
class ArrayColumnTest extends \PHPUnit_Framework_TestCase
{
    /**
     * testCreate.
     */
    public function testIsAssociative()
    {
        $arrayColumn = new ArrayColumn();
        $this->assertFalse($this->callMethod($arrayColumn, 'isAssociative', ['a', 'b']));
        $this->assertTrue($this->callMethod($arrayColumn, 'isAssociative', ['a' => 1, 'b' => 1]));
    }

    /**
     * testCreate.
     */
    public function testArrayToString()
    {
        $arrayColumn = new ArrayColumn();
        $result = $this->callMethod($arrayColumn, 'arrayToString', ['a', 'b' => [ 'd' => new \DateTime()]]);
        $this->assertNotEmpty($result);
        $this->assertTrue(is_string($result));
    }

    /**
     * @param $obj
     * @param $name
     * @param array $args
     * @return mixed
     * @throws \ReflectionException
     */
    public static function callMethod($obj, $name, array $args)
    {
        $class = new \ReflectionClass($obj);
        $method = $class->getMethod($name);
        $method->setAccessible(true);
        return $method->invokeArgs($obj, $args);
    }
}
