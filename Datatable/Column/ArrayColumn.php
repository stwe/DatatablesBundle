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
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class ArrayColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ArrayColumn extends Column
{
    /**
     * Count array elements.
     *
     * @var boolean
     */
    protected $count;

    /**
     * The counter is a link.
     *
     * @var Action
     */
    protected $countAction;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        if (empty($data) || !is_string($data)) {
            throw new InvalidArgumentException('setData(): Expecting non-empty string.');
        }

        if (false === strstr($data, '.')) {
            throw new InvalidArgumentException('setData(): An association is expected.');
        }

        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Column:array.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function addDataToOutputArray(&$row)
    {
        $actionRowItem = array();

        if (!empty($this->countAction)) {
            $actionRowItem[$this->countAction->getRoute()] = $this->countAction->isRenderIfClosure($row);
            $row['sg_datatables_actions'][$this->getIndex()] = $actionRowItem;
        } else {
            return null;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'array';
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->remove('editable');
        $resolver->remove('editable_if');

        $resolver->setRequired(array('data'));

        $resolver->setDefault('count', false);
        $resolver->setDefault('count_action', array());

        $resolver->setAllowedTypes('data', 'string');
        $resolver->setAllowedTypes('count', 'bool');
        $resolver->setAllowedTypes('count_action', 'array');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set count.
     *
     * @param boolean $count
     *
     * @return $this
     */
    public function setCount($count)
    {
        $this->count = (boolean) $count;

        return $this;
    }

    /**
     * Get count.
     *
     * @return boolean
     */
    public function getCount()
    {
        return (boolean) $this->count;
    }

    /**
     * Set count action.
     *
     * @param array $countAction
     *
     * @return $this
     */
    public function setCountAction(array $countAction)
    {
        if ($countAction) {
            $this->countAction = new Action();
            $this->countAction->setupOptionsResolver($countAction);
        }

        return $this;
    }

    /**
     * Get count action.
     *
     * @return Action
     */
    public function getCountAction()
    {
        return $this->countAction;
    }
}
