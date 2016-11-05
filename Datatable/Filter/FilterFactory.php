<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Filter;

use Exception;

/**
 * Class FilterFactory
 *
 * @package Sg\DatatablesBundle\Datatable\Filter
 */
class FilterFactory
{
    /**
     * Create Filter.
     *
     * @param string|FilterInterface $class
     *
     * @return FilterInterface
     * @throws Exception
     */
    public static function createFilter($class)
    {
        if (empty($class) || !is_string($class) && !$class instanceof FilterInterface) {
            throw new Exception('FilterFactory::createFilter(): String or FilterInterface expected.');
        }

        if ($class instanceof FilterInterface) {
            return $class;
        }

        if (is_string($class) && class_exists($class)) {
            $filter = new $class;

            if (!$filter instanceof FilterInterface) {
                throw new Exception('FilterFactory::createFilter(): FilterInterface expected.');
            } else {
                return $filter;
            }
        } else {
            throw new Exception("FilterFactory::createFilter(): $class is not callable.");
        }
    }
}
