<?php

namespace Sg\DatatablesBundle\Factory;

use Symfony\Bundle\TwigBundle\TwigEngine;

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
     * @return \Sg\DatatablesBundle\Datatable\AbstractDatatableView
     * @throws \Exception
     */
    public function getDatatableView($datatableViewClass)
    {
        if (!class_exists($datatableViewClass)) {
            throw new \Exception("Class {$datatableViewClass} not found.");
        }

        return new $datatableViewClass($this->templating);
    }
}
