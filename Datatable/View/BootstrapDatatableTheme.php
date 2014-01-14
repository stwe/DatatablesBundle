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
 * Class BootstrapDatatableTheme
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class BootstrapDatatableTheme extends AbstractDatatableTheme
{
    protected $sDomDefaultValues = array(
        'sDomLength'     => 'col-sm-4',
        'sDomFilter'     => 'col-sm-8',
        'sDomInfo'       => 'col-sm-3',
        'sDomPagination' => 'col-sm-9'
    );


    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'bootstrap3_datatable_theme';
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Theme:bootstrap3.html.twig';
    }
}