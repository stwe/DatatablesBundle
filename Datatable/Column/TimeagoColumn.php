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

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class TimeagoColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class TimeagoColumn extends AbstractColumn
{
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

        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Column:timeago.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'timeago';
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'class' => '',
            'classicon' => '',
            'padding' => '',
            'name' => '',
            'orderable' => true,
            'render' => 'render_timeago',
            'searchable' => true,
            'title' => '',
            'type' => '',
            'visible' => true,
            'width' => '',
            'search_type' => 'like',
            'filter_type' => 'text',
            'filter_options' => [],
            'filter_property' => '',
            'filter_search_column' => ''
        ));

        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('padding', 'string');
        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('title', 'string');
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('visible', 'bool');
        $resolver->setAllowedTypes('width', 'string');
        $resolver->setAllowedTypes('render', "string");
        $resolver->setAllowedTypes('orderable', 'bool');
        $resolver->setAllowedTypes('searchable', 'bool');
        $resolver->setAllowedTypes('search_type', 'string');
        $resolver->setAllowedTypes('filter_type', 'string');
        $resolver->setAllowedTypes('filter_options', 'array');
        $resolver->setAllowedTypes('filter_property', 'string');
        $resolver->setAllowedTypes('filter_search_column', 'string');

        $resolver->setAllowedValues('search_type', array('like', 'notLike', 'eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'in', 'notIn', 'isNull', 'isNotNull'));
        $resolver->setAllowedValues('filter_type', array('text', 'select'));

        return $this;
    }
}