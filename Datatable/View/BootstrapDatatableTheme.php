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
    /**
     * Default icon.
     *
     * @var string
     */
    const DEFAULT_ICON = 'glyphicon glyphicon-th';

    /**
     * Default show icon.
     *
     * @var string
     */
    const DEFAULT_SHOW_ICON = 'glyphicon glyphicon-eye-open';

    /**
     * Default edit icon.
     *
     * @var string
     */
    const DEFAULT_EDIT_ICON = 'glyphicon glyphicon-edit';

    /**
     * Default delete icon.
     *
     * @var string
     */
    const DEFAULT_DELETE_ICON = 'glyphicon glyphicon-trash';

    protected $sDomValues = array(
        'sDomLength'     => 'col-sm-4',
        'sDomFilter'     => 'col-sm-8',
        'sDomInfo'       => 'col-sm-3',
        'sDomPagination' => 'col-sm-9'
    );

    protected $tableClasses = 'table table-striped table-bordered';

    protected $formClasses = 'form-control input-sm';

    protected $pagination = 'bootstrap';

    protected $iconOk = 'glyphicon glyphicon-ok';

    protected $iconRemove = 'glyphicon glyphicon-remove';

    protected $actionButtonClasses = 'btn btn-default btn-xs';


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
        return 'SgDatatablesBundle:Datatable:datatable.html.twig';
    }
}