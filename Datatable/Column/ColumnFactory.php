<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class ColumnFactory
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ColumnFactory
{
    /**
     * Create Column by alias.
     *
     * @param string $alias
     *
     * @return ColumnInterface
     */
    public static function createColumnByAlias($alias)
    {
        if (empty($alias) || !is_string($alias) && !$alias instanceof ColumnInterface) {
            throw new InvalidArgumentException('createColumnByAlias(): String or ColumnInterface expected.');
        }

        if ($alias instanceof ColumnInterface) {
            return $alias;
        }

        switch (strtolower($alias)) {
            case 'action':
                $column = new ActionColumn();
                break;
            case 'array':
                $column = new ArrayColumn();
                break;
            case 'boolean':
                $column = new BooleanColumn();
                break;
            case 'column':
                $column = new Column();
                break;
            case 'datetime':
                $column = new DateTimeColumn();
                break;
            case 'timeago':
                $column = new TimeagoColumn();
                break;
            case 'multiselect':
                $column = new MultiselectColumn();
                break;
            case 'virtual':
                $column = new VirtualColumn();
                break;
            case 'image':
                $column = new ImageColumn();
                break;
            case 'gallery':
                $column = new GalleryColumn();
                break;
            case 'progress_bar':
                $column = new ProgressBarColumn();
                break;
            default:
                throw new InvalidArgumentException('createColumnByName(): The column is not supported.');
        }

        return $column;
    }
}
