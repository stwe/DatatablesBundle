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

use Symfony\Component\OptionsResolver\OptionsResolver;
use Exception;

/**
 * Class ActionColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ActionColumn extends AbstractColumn
{
    /**
     * The Actions container.
     * A required option.
     *
     * @var array
     */
    protected $actions;

    /**
     * HTML code before the <a> Tag.
     * Default: null
     *
     * @var null|string
     */
    protected $startHtml;

    /**
     * HTML code after the <a> Tag.
     * Default: null
     *
     * @var null|string
     */
    protected $endHtml;

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
    public function getTemplate()
    {
        return 'SgDatatablesBundle:column:column.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function addDataToOutputArray(array &$row)
    {
        $actionRowItems = array();

        /** @var Action $action */
        foreach ($this->actions as $action) {
            $actionRowItems[$action->getRoute()] = $action->callAddIfClosure($row);
        }

        $row['sg_datatables_actions'][$this->getIndex()] = $actionRowItems;
    }

    /**
     * {@inheritdoc}
     */
    public function renderContent(array &$row)
    {
        $parameters = array();

        /** @var Action $action */
        foreach ($this->actions as $action) {
            $routeParameters = $action->getRouteParameters();
            if (null !== $routeParameters) {
                foreach ($routeParameters as $key => $value) {
                    if (isset($row[$value])) {
                        $parameters[$key] = $row[$value];
                    } else {
                        $parameters[$key] = $value;
                    }
                }
            }
        }

        $row[$this->getIndex()] = $this->twig->render(
            'SgDatatablesBundle:render:action.html.twig',
            array(
                'actions' => $this->actions,
                'set_route_parameters' => $parameters,
                'add_if_actions' => $row['sg_datatables_actions'][$this->index],
                'start_html' => $this->startHtml,
                'end_html' => $this->endHtml,
            )
        );
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

        $resolver->remove('default_content');
        $resolver->remove('orderable');
        $resolver->remove('order_data');
        $resolver->remove('order_sequence');
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
                $newAction = new Action();
                $this->actions[] = $newAction->set($action);
            }
        } else {
            throw new Exception('ActionColumn::setActions(): The actions array should contain at least one element.');
        }

        return $this;
    }

    /**
     * Get startHtml.
     *
     * @return null|string
     */
    public function getStartHtml()
    {
        return $this->startHtml;
    }

    /**
     * Set startHtml.
     *
     * @param null|string $startHtml
     *
     * @return $this
     */
    public function setStartHtml($startHtml)
    {
        $this->startHtml = $startHtml;

        return $this;
    }

    /**
     * Get endHtml.
     *
     * @return null|string
     */
    public function getEndHtml()
    {
        return $this->endHtml;
    }

    /**
     * Set endHtml.
     *
     * @param null|string $endHtml
     *
     * @return $this
     */
    public function setEndHtml($endHtml)
    {
        $this->endHtml = $endHtml;

        return $this;
    }
}
