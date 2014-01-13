<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\View;

/**
 * Class JqueryUiDatatableTheme
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class JqueryUiDatatableTheme extends AbstractDatatableTheme
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'jqueryui_datatable_theme';
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Theme:jqueryui.html.twig';
    }
}