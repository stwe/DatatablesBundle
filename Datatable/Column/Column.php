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

/**
 * Class Column
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class Column extends AbstractDataColumn
{
    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return "SgDatatablesBundle:Column:column.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return "column";
    }
}
