<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Sg\DatatablesBundle\Datatable\Action\Action;
use Sg\DatatablesBundle\Datatable\HtmlContainerTrait;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Closure;
use Exception;

/**
 * Class ActionColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ActionColumn extends AbstractColumn
{
    /**
     * This Column has a 'start_html' and a 'end_html' option.
     * <startHtml> action1 action2 actionX </endHtml>
     */
    use HtmlContainerTrait;

    /**
     * The Actions container.
     * A required option.
     *
     * @var array
     */
    protected $actions;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function dqlConstraint($dql)
    {
        return null === $dql ? true : false;
    }

    /**
     * {@inheritdoc}
     */
    public function isSelectColumn()
    {
        return false;
    }

    /**
     * {@inheritdoc}
     */
    public function addDataToOutputArray(array &$row)
    {
        $actionRowItems = array();

        /** @var Action $action */
        foreach ($this->actions as $actionKey => $action) {
            $actionRowItems[$actionKey] = $action->callRenderIfClosure($row);
        }

        $row['sg_datatables_actions'][$this->getIndex()] = $actionRowItems;
    }

    /**
     * Flattens an nested array representing a row.
     *
     * The scheme used is:
     *   'key' => array('key2' => array('key3' => 'value'))
     * Becomes:
     *   'key.key2.key3' => 'value'
     *
     * This function takes an array by reference and will modify it.
     *
     * @param array  &$messages The array that will be flattened
     * @param array  $subnode   Current subnode being parsed, used internally for recursive calls
     * @param string $path      Current path being parsed, used internally for recursive calls
     */
    private function flattenRow(array &$messages, array $subnode = null, $path = null)
    {
        if (null === $subnode) {
            $subnode = &$messages;
        }
        foreach ($subnode as $key => $value) {
            if (is_array($value)) {
                $nodePath = $path ? $path.'.'.$key : $key;
                $this->flattenRow($messages, $value, $nodePath);
                if (null === $path) {
                    unset($messages[$key]);
                }
            } elseif (null !== $path) {
                $messages[$path.'.'.$key] = $value;
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function renderSingleField(array &$row)
    {
        $parameters = array();
        $values = array();

        $flatRow = $row;
        $this->flattenRow($flatRow);

        /** @var Action $action */
        foreach ($this->actions as $actionKey => $action) {
            $routeParameters = $action->getRouteParameters();
            if (is_array($routeParameters)) {
                foreach ($routeParameters as $key => $value) {
                    if (isset($flatRow[$value])) {
                        $parameters[$actionKey][$key] = $flatRow[$value];
                    } else {
                        $parameters[$actionKey][$key] = $value;
                    }
                }
            } elseif ($routeParameters instanceof Closure) {
                $parameters[$actionKey] = call_user_func($routeParameters, $row);
            } else {
                $parameters[$actionKey] = array();
            }

            if ($action->isButton()) {
                if (null !== $action->getButtonValue()) {
                    if (isset($row[$action->getButtonValue()])) {
                        $values[$actionKey] = $row[$action->getButtonValue()];
                    } else {
                        $values[$actionKey] = $action->getButtonValue();
                    }

                    if (is_bool($values[$actionKey])) {
                        $values[$actionKey] = (int) $values[$actionKey];
                    }

                    if (true === $action->isButtonValuePrefix()) {
                        $values[$actionKey] = 'sg-datatables-'.$this->getDatatableName().'-action-button-'.$actionKey.'-'.$values[$actionKey];
                    }
                } else {
                    $values[$actionKey] = null;
                }
            }
        }

        $row[$this->getIndex()] = $this->twig->render(
            $this->getCellContentTemplate(),
            array(
                'actions' => $this->actions,
                'route_parameters' => $parameters,
                'values' => $values,
                'render_if_actions' => $row['sg_datatables_actions'][$this->index],
                'start_html_container' => $this->startHtml,
                'end_html_container' => $this->endHtml,
            )
        );
    }

    /**
     * {@inheritdoc}
     */
    public function renderToMany(array &$row)
    {
        throw new Exception('ActionColumn::renderToMany(): This function should never be called.');
    }

    /**
     * {@inheritdoc}
     */
    public function getCellContentTemplate()
    {
        return 'SgDatatablesBundle:render:action.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnType()
    {
        return parent::ACTION_COLUMN;
    }

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * Config options.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->remove('dql');
        $resolver->remove('data');
        $resolver->remove('default_content');

        // the 'orderable' option is removed, but via getter it returns 'false' for the view
        $resolver->remove('orderable');
        $resolver->remove('order_data');
        $resolver->remove('order_sequence');

        // the 'searchable' option is removed, but via getter it returns 'false' for the view
        $resolver->remove('searchable');

        $resolver->remove('join_type');
        $resolver->remove('type_of_field');

        $resolver->setRequired(array('actions'));

        $resolver->setDefaults(array(
            'start_html' => null,
            'end_html' => null,
        ));

        $resolver->setAllowedTypes('actions', 'array');
        $resolver->setAllowedTypes('start_html', array('null', 'string'));
        $resolver->setAllowedTypes('end_html', array('null', 'string'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get actions.
     *
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Set actions.
     *
     * @param array $actions
     *
     * @return $this
     * @throws Exception
     */
    public function setActions(array $actions)
    {
        if (count($actions) > 0) {
            foreach ($actions as $action) {
                $newAction = new Action($this->datatableName);
                $this->actions[] = $newAction->set($action);
            }
        } else {
            throw new Exception('ActionColumn::setActions(): The actions array should contain at least one element.');
        }

        return $this;
    }

    /**
     * Get orderable.
     *
     * @return bool
     */
    public function getOrderable()
    {
        return false;
    }

    /**
     * Get searchable.
     *
     * @return bool
     */
    public function getSearchable()
    {
        return false;
    }
}
