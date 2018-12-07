<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable;

/**
 * Class Style
 */
class Style
{
    //-------------------------------------------------
    // Styling classes
    //-------------------------------------------------

    /**
     * Default style.
     */
    public const BASE_STYLE = 'display';

    /**
     * Default style with none of the additional feature style classes.
     */
    public const BASE_STYLE_NO_CLASSES = '';

    /**
     * Default style with cell border.
     */
    public const BASE_STYLE_CELL_BORDERS = 'cell-border';

    /**
     * Reduce the amount of white-spaces.
     */
    public const BASE_STYLE_COMPACT = 'display compact';

    /**
     * Default style with hover class.
     */
    public const BASE_STYLE_HOVER = 'hover';

    /**
     * Default style with order-column class.
     */
    public const BASE_STYLE_ORDER_COLUMN = 'order-column';

    /**
     * Default style with row border.
     */
    public const BASE_STYLE_ROW_BORDERS = 'row-border';

    /**
     * Default style with stripe class.
     */
    public const BASE_STYLE_STRIPE = 'stripe';

    /**
     * Bootstrap3 table styling options.
     */
    public const BOOTSTRAP_3_STYLE = 'table table-striped table-bordered';

    /**
     * Foundations's table styling options.
     */
    public const FOUNDATION_STYLE = 'display';

    /**
     * Semantic UI styling options.
     */
    public const SEMANTIC_UI_STYLE = 'ui celled table';

    /**
     * jQuery UI's ThemeRoller styles.
     */
    public const JQUERY_UI_STYLE = 'display';

    /**
     * Bootstrap4 table styling options.
     */
    public const BOOTSTRAP_4_STYLE = 'table table-striped table-bordered';

    /**
     * Material Design style.
     */
    public const MATERIAL_DESIGN = 'mdl-data-table';

    /**
     * Uikit styling options.
     */
    public const UI_KIT = 'uk-table uk-table-hover uk-table-striped';

    //-------------------------------------------------
    // Built-in paging button arrangements
    //-------------------------------------------------

    /**
     * Page number buttons only.
     */
    public const NUMBERS_PAGINATION = 'numbers';

    /**
     * "Previous" and "Next" buttons only.
     */
    public const SIMPLE_PAGINATION = 'simple';

    /**
     * "Previous" and "Next" buttons, plus page numbers.
     */
    public const SIMPLE_NUMBERS_PAGINATION = 'simple_numbers';

    /**
     * "First", "Previous", "Next" and "Last" buttons.
     */
    public const FULL_PAGINATION = 'full';

    /**
     * "First", "Previous", "Next" and "Last" buttons, plus page numbers.
     */
    public const FULL_NUMBERS_PAGINATION = 'full_numbers';

    /**
     * "First" and "Last" buttons, plus page numbers.
     */
    public const FIRST_LAST_NUMBERS_PAGINATION = 'first_last_numbers';
}
