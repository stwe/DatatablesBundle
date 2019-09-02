<?php

/*
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
 * @internal
 * @coversNothing
 */
final class ArrayColumnTest extends \PHPUnit\Framework\TestCase
{
    public function testIsAssociative()
    {
        $arrayColumn = new ArrayColumn();
        static::assertFalse($this->callMethod($arrayColumn, 'isAssociative', [['a', 'b']]));
        static::assertTrue($this->callMethod($arrayColumn, 'isAssociative', [['a' => 1, 'b' => 1]]));
    }

    public function testArrayToString()
    {
        $arrayColumn = new ArrayColumn();
        $result = $this->callMethod($arrayColumn, 'arrayToString', [['a', 'b' => ['d' => new \DateTime()]]]);
        static::assertNotEmpty($result);
        static::assertInternalType('string', $result);
    }

    /**
     * @param $obj
     * @param $name
     *
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
