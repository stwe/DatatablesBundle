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
 * Interface DatatableThemeInterface
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
interface DatatableThemeInterface
{
    /**
     * Get name of the theme.
     *
     * @return string
     */
    public function getName();

    /**
     * Get the name of the twig template.
     *
     * @return string
     */
    public function getTemplate();

    /**
     * Get the sDom default values for the theme.
     *
     * @return array
     */
    public function getSDomDefaultValues();
} 