<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Factory;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Exception;

/**
 * Class DatatableFactory
 *
 * @package Sg\DatatablesBundle\Factory
 */
class DatatableFactory
{
    /**
     * @var TwigEngine
     */
    protected $templating;


    /**
     * Ctor.
     *
     * @param TwigEngine $templating
     */
    public function __construct(TwigEngine $templating)
    {
        $this->templating = $templating;
    }

    /**
     * Returns an instance of the datatableViewClass.
     *
     * @param string $datatableViewClass The class name
     *
     * @throws Exception
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatableView
     */
    public function getDatatableView($datatableViewClass)
    {
        if (!class_exists($datatableViewClass)) {
            throw new Exception("Class {$datatableViewClass} not found.");
        }

        return new $datatableViewClass($this->templating);
    }
}
