<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Twig;

use Closure;
use Sg\DatatablesBundle\Datatable\Action\Action;
use Sg\DatatablesBundle\Datatable\Column\ColumnInterface;
use Sg\DatatablesBundle\Datatable\DatatableInterface;
use Sg\DatatablesBundle\Datatable\Filter\FilterInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use Twig_Environment;
use Twig_Extension;
use Twig_SimpleFilter;
use Twig_SimpleFunction;

class DatatableTwigExtension extends Twig_Extension
{
    /**
     * The PropertyAccessor.
     *
     * @var PropertyAccessor
     */
    protected $accessor;

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
        return [
            new Twig_SimpleFunction(
                'sg_datatables_render',
                [$this, 'datatablesRender'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
            new Twig_SimpleFunction(
                'sg_datatables_render_html',
                [$this, 'datatablesRenderHtml'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
            new Twig_SimpleFunction(
                'sg_datatables_render_js',
                [$this, 'datatablesRenderJs'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
            new Twig_SimpleFunction(
                'sg_datatables_render_filter',
                [$this, 'datatablesRenderFilter'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
            new Twig_SimpleFunction(
                'sg_datatables_render_multiselect_actions',
                [$this, 'datatablesRenderMultiselectActions'],
                ['is_safe' => ['html'], 'needs_environment' => true]
            ),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getFilters()
    {
        return [
            new Twig_SimpleFilter('sg_datatables_bool_var', [$this, 'boolVar']),
        ];
    }

    //-------------------------------------------------
    // Functions
    //-------------------------------------------------

    /**
     * Renders the template.
     *
     * @return string
     */
    public function datatablesRender(Twig_Environment $twig, DatatableInterface $datatable)
    {
        return $twig->render(
            '@SgDatatables/datatable/datatable.html.twig',
            [
                'sg_datatables_view' => $datatable,
            ]
        );
    }

    /**
     * Renders the html template.
     *
     * @return string
     */
    public function datatablesRenderHtml(Twig_Environment $twig, DatatableInterface $datatable)
    {
        return $twig->render(
            '@SgDatatables/datatable/datatable_html.html.twig',
            [
                'sg_datatables_view' => $datatable,
            ]
        );
    }

    /**
     * Renders the js template.
     *
     * @return string
     */
    public function datatablesRenderJs(Twig_Environment $twig, DatatableInterface $datatable)
    {
        return $twig->render(
            '@SgDatatables/datatable/datatable_js.html.twig',
            [
                'sg_datatables_view' => $datatable,
            ]
        );
    }

    /**
     * Renders a Filter template.
     *
     * @param string $position
     *
     * @return string
     */
    public function datatablesRenderFilter(Twig_Environment $twig, DatatableInterface $datatable, ColumnInterface $column, $position)
    {
        /** @var FilterInterface $filter */
        $filter = $this->accessor->getValue($column, 'filter');
        $index = $this->accessor->getValue($column, 'index');
        $searchColumn = $this->accessor->getValue($filter, 'searchColumn');

        if (null !== $searchColumn) {
            $columns = $datatable->getColumnBuilder()->getColumnNames();
            $searchColumnIndex = $columns[$searchColumn];
        } else {
            $searchColumnIndex = $index;
        }

        return $twig->render(
            $filter->getTemplate(),
            [
                'column' => $column,
                'search_column_index' => $searchColumnIndex,
                'datatable_name' => $datatable->getName(),
                'position' => $position,
            ]
        );
    }

    /**
     * Renders the MultiselectColumn Actions.
     *
     * @param int $pipeline
     *
     * @return string
     */
    public function datatablesRenderMultiselectActions(Twig_Environment $twig, ColumnInterface $multiselectColumn, $pipeline)
    {
        $parameters = [];
        $values = [];
        $actions = $this->accessor->getValue($multiselectColumn, 'actions');
        $domId = $this->accessor->getValue($multiselectColumn, 'renderActionsToId');
        $datatableName = $this->accessor->getValue($multiselectColumn, 'datatableName');

        /** @var Action $action */
        foreach ($actions as $actionKey => $action) {
            $routeParameters = $action->getRouteParameters();
            if (\is_array($routeParameters)) {
                foreach ($routeParameters as $key => $value) {
                    $parameters[$actionKey][$key] = $value;
                }
            } elseif ($routeParameters instanceof Closure) {
                $parameters[$actionKey] = \call_user_func($routeParameters);
            } else {
                $parameters[$actionKey] = [];
            }

            if ($action->isButton()) {
                if (null !== $action->getButtonValue()) {
                    $values[$actionKey] = $action->getButtonValue();

                    if (\is_bool($values[$actionKey])) {
                        $values[$actionKey] = (int) $values[$actionKey];
                    }

                    if (true === $action->isButtonValuePrefix()) {
                        $values[$actionKey] = 'sg-datatables-'.$datatableName.'-multiselect-button-'.$actionKey.'-'.$values[$actionKey];
                    }
                } else {
                    $values[$actionKey] = null;
                }
            }
        }

        return $twig->render(
            '@SgDatatables/datatable/multiselect_actions.html.twig',
            [
                'actions' => $actions,
                'route_parameters' => $parameters,
                'values' => $values,
                'datatable_name' => $datatableName,
                'dom_id' => $domId,
                'pipeline' => $pipeline,
            ]
        );
    }

    //-------------------------------------------------
    // Filters
    //-------------------------------------------------

    /**
     * Renders: {{ var ? 'true' : 'false' }}.
     *
     * @return string
     */
    public function boolVar($value)
    {
        if ($value) {
            return 'true';
        }

        return 'false';
    }
}
