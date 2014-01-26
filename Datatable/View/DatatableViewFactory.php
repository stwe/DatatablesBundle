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

use Sg\DatatablesBundle\Column\ColumnBuilderInterface;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Translation\Translator;
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
    private $templating;

    /**
     * The translator service.
     *
     * @var Translator
     */
    private $translator;

    /**
     * The router service.
     *
     * @var Router
     */
    private $router;

    /**
     * The default layout options.
     *
     * @var array
     */
    private $layoutOptions;

    /**
     * A ColumnBuilderInterface.
     *
     * @var ColumnBuilderInterface
     */
    private $columnBuilder;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param TwigEngine             $templating    The templating service
     * @param Translator             $translator    The translator service
     * @param Router                 $router        The router service
     * @param array                  $layoutOptions The default layout options
     * @param ColumnBuilderInterface $columnBuilder A column builder
     */
    public function __construct(TwigEngine $templating, Translator $translator, Router $router, array $layoutOptions, ColumnBuilderInterface $columnBuilder)
    {
        $this->templating = $templating;
        $this->translator = $translator;
        $this->router = $router;
        $this->layoutOptions = $layoutOptions;
        $this->columnBuilder = $columnBuilder;
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
        $datatableView = new $datatableViewClass($this->templating, $this->translator, $this->router, $this->layoutOptions, $this->columnBuilder);
        $datatableView->buildDatatableView();

        return $datatableView;
    }
}
