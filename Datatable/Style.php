<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable;

class Style
{
    //-------------------------------------------------
    // Styling classes
    //-------------------------------------------------

    /**
     * Default style.
     *
     * @var string
     */
    public const BASE_STYLE = 'display';

    /**
     * Default style with none of the additional feature style classes.
     *
     * @var string
     */
    public const BASE_STYLE_NO_CLASSES = '';

    /**
     * Default style with cell border.
     *
     * @var string
     */
    public const BASE_STYLE_CELL_BORDERS = 'cell-border';

    /**
     * Reduce the amount of white-spaces.
     *
     * @var string
     */
    public const BASE_STYLE_COMPACT = 'display compact';

    /**
     * Default style with hover class.
     *
     * @var string
     */
    public const BASE_STYLE_HOVER = 'hover';

    /**
     * Default style with order-column class.
     *
     * @var string
     */
    public const BASE_STYLE_ORDER_COLUMN = 'order-column';

    /**
     * Default style with row border.
     *
     * @var string
     */
    public const BASE_STYLE_ROW_BORDERS = 'row-border';

    /**
     * Default style with stripe class.
     *
     * @var string
     */
    public const BASE_STYLE_STRIPE = 'stripe';

    /**
     * Bootstrap3 table styling options.
     *
     * @var string
     */
    public const BOOTSTRAP_3_STYLE = 'table table-striped table-bordered';

    /**
     * Foundations's table styling options.
     *
     * @var string
     */
    public const FOUNDATION_STYLE = 'display';

    /**
     * Semantic UI styling options.
     *
     * @var string
     */
    public const SEMANTIC_UI_STYLE = 'ui celled table';

    /**
     * jQuery UI's ThemeRoller styles.
     *
     * @var string
     */
    public const JQUERY_UI_STYLE = 'display';

    /**
     * Bootstrap4 table styling options.
     *
     * @var string
     */
    public const BOOTSTRAP_4_STYLE = 'table table-striped table-bordered';

    /**
     * Material Design style.
     *
     * @var string
     */
    public const MATERIAL_DESIGN = 'mdl-data-table';

    /**
     * Uikit styling options.
     *
     * @var string
     */
    public const UI_KIT = 'uk-table uk-table-hover uk-table-striped';

    //-------------------------------------------------
    // Built-in paging button arrangements
    //-------------------------------------------------

    /**
     * Page number buttons only.
     *
     * @var string
     */
    public const NUMBERS_PAGINATION = 'numbers';

    /**
     * "Previous" and "Next" buttons only.
     *
     * @var string
     */
    public const SIMPLE_PAGINATION = 'simple';

    /**
     * "Previous" and "Next" buttons, plus page numbers.
     *
     * @var string
     */
    public const SIMPLE_NUMBERS_PAGINATION = 'simple_numbers';

    /**
     * "First", "Previous", "Next" and "Last" buttons.
     *
     * @var string
     */
    public const FULL_PAGINATION = 'full';

    /**
     * "First", "Previous", "Next" and "Last" buttons, plus page numbers.
     *
     * @var string
     */
    public const FULL_NUMBERS_PAGINATION = 'full_numbers';

    /**
     * "First" and "Last" buttons, plus page numbers.
     *
     * @var string
     */
    public const FIRST_LAST_NUMBERS_PAGINATION = 'first_last_numbers';
}
