<?php

/**
 * This file is part of the WgUniversalDataTableBundle package.
 *
 * (c) stwe <https://github.com/stwe/DataTablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wg\UniversalDataTable\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class Column
 *
 * @package Wg\UniversalDataTable\DataTable\Column
 */
class Column extends AbstractColumn
{
    /**
     * Default content.
     *
     * @var string
     */
    protected $default;

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
        return 'WgUniversalDataTableBundle:Column:column.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'column';
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
            'padding' => '',
            'name' => '',
            'orderable' => true,
            'render' => null,
            'searchable' => true,
            'title' => '',
            'type' => '',
            'visible' => true,
            'width' => '',
            'default' => '',
            'search_type' => 'like',
            'filter_type' => 'text',
            'filter_options' => [],
            'filter_property' => '',
        ));

        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('padding', 'string');
        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('orderable', 'bool');
        $resolver->setAllowedTypes('render', array('string', 'null'));
        $resolver->setAllowedTypes('searchable', 'bool');
        $resolver->setAllowedTypes('title', 'string');
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('visible', 'bool');
        $resolver->setAllowedTypes('width', 'string');
        $resolver->setAllowedTypes('default', 'string');
        $resolver->setAllowedTypes('search_type', 'string');
        $resolver->setAllowedTypes('filter_type', 'string');
        $resolver->setAllowedTypes('filter_options', 'array');
        $resolver->setAllowedTypes('filter_property', 'string');

        $resolver->setAllowedValues('search_type', array('like', 'notLike', 'eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'in', 'notIn', 'isNull', 'isNotNull'));
        $resolver->setAllowedValues('filter_type', array('text', 'select'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get default.
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Set default.
     *
     * @param string $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }
}
