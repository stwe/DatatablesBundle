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

use Sg\DatatablesBundle\Column\ColumnBuilder;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Exception;

/**
 * Class DatatableViewFactory
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class DatatableViewFactory implements DatatableViewFactoryInterface
{
    /**
     * The templating service.
     *
     * @var TwigEngine
     */
    protected $templating;

    /**
     * The default layout options.
     *
     * @var array
     */
    protected $layoutOptions;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param TwigEngine $templating    The templating service
     * @param array      $layoutOptions The default layout options
     */
    public function __construct(TwigEngine $templating, array $layoutOptions)
    {
        $this->templating = $templating;
        $this->layoutOptions = $layoutOptions;
    }


    //-------------------------------------------------
    // DatatableViewFactoryInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function createDatatableView($datatableViewClass)
    {
        if (!class_exists($datatableViewClass)) {
            throw new Exception("Class {$datatableViewClass} not found.");
        }

        if (!in_array('Sg\DatatablesBundle\Datatable\View\DatatableViewInterface', class_implements($datatableViewClass))) {
            throw new Exception("Class {$datatableViewClass} implements not the DatatableViewInterface.");
        }

        /**
         * @var DatatableViewInterface $datatableView
         */
        $datatableView = new $datatableViewClass($this->templating, $this->layoutOptions, new ColumnBuilder());
        $datatableView->buildDatatableView();

        return $datatableView;
    }
}
