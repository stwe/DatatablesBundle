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

use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class FilterFactory
 *
 * @package Sg\DatatablesBundle\Datatable\Filter
 */
class FilterFactory
{
    /**
     * Create filter by type.
     *
     * @param $type
     *
     * @return FilterInterface
     */
    public static function createFilterByType($type)
    {
        if (empty($type) || !is_string($type) && !$type instanceof FilterInterface) {
            throw new InvalidArgumentException('createFilterByType(): String or FilterInterface expected.');
        }

        if ($type instanceof FilterInterface) {
            return $type;
        }

        switch (strtolower($type)) {
            case 'text':
                return new TextFilter();
                break;
            case 'select':
                return new SelectFilter();
                break;
            case 'multiselect':
                return new MultiSelectFilter();
                break;
            case 'select2':
                return new Select2Filter();
                break;
            case 'daterange':
                return new DateRangeFilter();
                break;
            case 'slider':
                return new SliderFilter();
                break;
            default:
                throw new InvalidArgumentException('createFilterByType(): The filter-type is not supported.');
        }
    }
}
