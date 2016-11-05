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
use Sg\DatatablesBundle\Datatable\Column\ColumnInterface;
use Sg\DatatablesBundle\Datatable\Filter\FilterInterface;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
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
    /**
     * The PropertyAccessor.
     *
     * @var PropertyAccessor
     */
    protected $accessor;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * DatatableTwigExtension constructor.
     */
    public function __construct()
    {
        $this->accessor = PropertyAccess::createPropertyAccessor();
    }

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
            ),
            new Twig_SimpleFunction(
                'sg_datatable_render_filter',
                array($this, 'datatableRenderFilter'),
                array('is_safe' => array('html'), 'needs_environment' => true)
            ),
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

    /**
     * Renders a Filter template.
     *
     * @param Twig_Environment   $twig
     * @param DatatableInterface $datatable
     * @param ColumnInterface    $column
     * @param string             $position
     *
     * @return string
     */
    public function datatableRenderFilter(Twig_Environment $twig, DatatableInterface $datatable, ColumnInterface $column, $position)
    {
        /** @var FilterInterface $filter */
        $filter = $this->accessor->getValue($column, 'filter');
        $index = $this->accessor->getValue($column, 'index');
        $searchColumn = $this->accessor->getValue($filter, 'searchColumn');

        if (null !== $searchColumn) {
            $columns = $datatable->getColumnNames();
            $searchColumnIndex = $columns[$searchColumn];
        } else {
            $searchColumnIndex = $index;
        }

        return $twig->render($filter->getTemplate(), array(
                'column' => $column,
                'search_column_index' => $searchColumnIndex,
                'datatable_name' => $datatable->getName(),
                'position' => $position
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
