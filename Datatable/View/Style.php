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
 * Class Style
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class Style
{
    //-------------------------------------------------
    //  Style file features
    //-------------------------------------------------

    /**
     * Default style.
     *
     * @var string
     */
    const BASE_STYLE = 'display';

    /**
     * Default style with none of the additional feature style classes.
     *
     * @var string
     */
    const BASE_STYLE_NO_CLASSES = '';

    /**
     * Default style with row border.
     *
     * @var string
     */
    const BASE_STYLE_ROW_BORDERS = 'row-border';

    /**
     * Default style with cell border.
     *
     * @var string
     */
    const BASE_STYLE_CELL_BORDERS = 'cell-border';

    /**
     * Default style with hover class.
     *
     * @var string
     */
    const BASE_STYLE_HOVER = 'hover';

    /**
     * Default style with order-column class.
     *
     * @var string
     */
    const BASE_STYLE_ORDER_COLUMN = 'order-column';

    /**
     * Default style with stripe class.
     *
     * @var string
     */
    const BASE_STYLE_STRIPE = 'stripe';

    /**
     * jQuery UI's ThemeRoller styles.
     *
     * @var string
     */
    const JQUERY_UI_STYLE = 'display';

    /**
     * Bootstrap's table styling options.
     *
     * @var string
     */
    const BOOTSTRAP_3_STYLE = 'table table-striped table-bordered';

    /**
     * Foundations's table styling options.
     *
     * @var string
     */
    const FOUNDATION_STYLE = 'display';

    //-------------------------------------------------
    // Built-in paging button arrangements
    //-------------------------------------------------

    /**
     * "Previous" and "Next" buttons only.
     *
     * @var string
     */
    const SIMPLE_PAGINATION = 'simple';

    /**
     * "Previous" and "Next" buttons, plus page numbers.
     *
     * @var string
     */
    const SIMPLE_NUMBERS_PAGINATION = 'simple_numbers';

    /**
     * "First", "Previous", "Next" and "Last" buttons.
     *
     * @var string
     */
    const FULL_PAGINATION = 'full';

    /**
     * "First", "Previous", "Next" and "Last" buttons, plus page numbers.
     *
     * @var string
     */
    const FULL_NUMBERS_PAGINATION = 'full_numbers';
}
