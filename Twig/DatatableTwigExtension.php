<?php

namespace Sg\DatatablesBundle\Twig;

use Twig_Extension;
use Twig_Function_Method;
use Sg\DatatablesBundle\Datatable\AbstractDatatableView;

/**
 * Class DatatableTwigExtension
 *
 * @package Sg\DatatablesBundle\Twig
 */
class DatatableTwigExtension extends Twig_Extension
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'sg_datatables_twig_extension';
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'datatable_render' => new Twig_Function_Method($this, 'datatableRender', array('is_safe' => array('html')))
        );
    }

    /**
     * Renders the template.
     *
     * @param AbstractDatatableView $datatable
     *
     * @return string
     */
    public function datatableRender(AbstractDatatableView $datatable)
    {
        return $datatable->createView();
    }
}
