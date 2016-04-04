<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Tomáš Polívka <draczris@gmail.com>
 * @author stwe
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Sg\DatatablesBundle\Datatable\Action\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class ActionColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ActionColumn extends AbstractColumn
{
    /**
     * Start HTML.
     *
     * @var string
     */
    protected $startHtml;

    /**
     * End HTML.
     *
     * @var string
     */
    protected $endHtml;

    /**
     * The actions container.
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
    public function setData($data)
    {
        if (null !== $data) {
            throw new InvalidArgumentException('setData(): Null expected.');
        }

        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Column:action.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'action';
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('actions'));

        $resolver->setDefaults(array(
            'class' => '',
            'padding' => '',
            'name' => '',
            'title' => '',
            'type' => '',
            'visible' => true,
            'width' => '',
            'start_html' => '',
            'end_html' => ''
        ));

        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('padding', 'string');
        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('title', 'string');
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('visible', 'bool');
        $resolver->setAllowedTypes('width', 'string');
        $resolver->setAllowedTypes('start_html', 'string');
        $resolver->setAllowedTypes('end_html', 'string');
        $resolver->setAllowedTypes('actions', 'array');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set start HTML.
     *
     * @param string $startHtml
     *
     * @return $this
     */
    public function setStartHtml($startHtml)
    {
        $this->startHtml = $startHtml;

        return $this;
    }

    /**
     * Get start HTML.
     *
     * @return string
     */
    public function getStartHtml()
    {
        return $this->startHtml;
    }

    /**
     * Set end HTML.
     *
     * @param string $endHtml
     *
     * @return $this
     */
    public function setEndHtml($endHtml)
    {
        $this->endHtml = $endHtml;

        return $this;
    }

    /**
     * Get end HTML.
     *
     * @return string
     */
    public function getEndHtml()
    {
        return $this->endHtml;
    }

    /**
     * Set actions.
     *
     * @param array $actions
     *
     * @return $this
     */
    public function setActions(array $actions)
    {
        foreach ($actions as $action) {
            $newAction = new Action();
            $this->actions[] = $newAction->setupOptionsResolver($action);
        }

        return $this;
    }

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
     * @param array $entity
     */
    public function checkVisibility(array &$entity)
    {
        $actionState = [];
        /** @var Action $action */
        foreach ($this->actions as $action) {
            $actionState[$action->getRoute()] = $action->isVisible($entity);
        }

        //return the entity with actions state
        $entity['actions'] = $actionState;
    }
}
