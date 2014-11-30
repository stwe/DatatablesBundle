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
 * Class TimeagoColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class TimeagoColumn extends AbstractDataRenderColumn
{
    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        parent::setDefaults();

        $this->setRender("render_timeago");

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return "SgDatatablesBundle:Column:timeago.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return "timeago";
    }
}
