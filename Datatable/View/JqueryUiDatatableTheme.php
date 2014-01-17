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
     * Default icon.
     *
     * @var string
     */
    const DEFAULT_ICON = 'ui-icon ui-icon-folder-collapsed';

    /**
     * Default show icon.
     *
     * @var string
     */
    const DEFAULT_SHOW_ICON = 'ui-icon ui-icon-zoomin';

    /**
     * Default edit icon.
     *
     * @var string
     */
    const DEFAULT_EDIT_ICON = 'ui-icon ui-icon-pencil';

    /**
     * Default delete icon.
     *
     * @var string
     */
    const DEFAULT_DELETE_ICON = 'ui-icon ui-icon-trash';

    protected $iconOk = 'ui-icon ui-icon-circle-check';

    protected $iconRemove = 'ui-icon ui-icon-circle-close';


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
        return 'SgDatatablesBundle:Theme:base.html.twig';
    }
}