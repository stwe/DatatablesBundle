<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Twig;

use Sg\DatatablesBundle\Datatable\DatatableInterface;

use Twig_Environment;
use Twig_Extension;
use Twig_SimpleFunction;
use Twig_SimpleFilter;

/**
 * Class DatatableTwigExtension
 *
 * @package Sg\DatatablesBundle\Twig
 */
class DatatableTwigExtension extends Twig_Extension
{
    //-------------------------------------------------
    // Twig_ExtensionInterface
    //-------------------------------------------------

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
            new Twig_SimpleFunction(
                'sg_datatable_render',
                array($this, 'datatableRender'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new Twig_SimpleFunction(
                'sg_datatable_render_html',
                array($this, 'datatableRenderHtml'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
            new Twig_SimpleFunction(
                'sg_datatable_render_js',
                array($this, 'datatableRenderJs'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return array(
            new Twig_SimpleFilter('sg_datatable_bool_var', array($this, 'boolVar')),
        );
    }

    //-------------------------------------------------
    // Functions
    //-------------------------------------------------

    /**
     * Renders the template.
     *
     * @param Twig_Environment   $twig
     * @param DatatableInterface $datatable
     *
     * @return string
     */
    public function datatableRender(Twig_Environment $twig, DatatableInterface $datatable)
    {
        return $twig->render(
            'SgDatatablesBundle:datatable:datatable.html.twig',
            array(
                'sg_datatable_view' => $datatable
            )
        );
    }

    /**
     * Renders the html template.
     *
     * @param Twig_Environment   $twig
     * @param DatatableInterface $datatable
     *
     * @return string
     */
    public function datatableRenderHtml(Twig_Environment $twig, DatatableInterface $datatable)
    {
        return $twig->render(
            'SgDatatablesBundle:datatable:datatable_html.html.twig',
            array(
                'sg_datatable_view' => $datatable,
            )
        );
    }

    /**
     * Renders the js template.
     *
     * @param Twig_Environment   $twig
     * @param DatatableInterface $datatable
     *
     * @return string
     */
    public function datatableRenderJs(Twig_Environment $twig, DatatableInterface $datatable)
    {
        return $twig->render(
            'SgDatatablesBundle:datatable:datatable_js.html.twig',
            array(
                'sg_datatable_view' => $datatable,
            )
        );
    }

    //-------------------------------------------------
    // Filters
    //-------------------------------------------------

    /**
     * Renders: {{ var ? 'true' : 'false' }}
     *
     * @param $value
     *
     * @return string
     */
    public function boolVar($value)
    {
        if ($value) {
            return 'true';
        } else {
            return 'false';
        }
    }
}
