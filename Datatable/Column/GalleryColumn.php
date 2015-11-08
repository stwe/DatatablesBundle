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
 * Class GalleryColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class GalleryColumn extends ImageColumn
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

        if (false === strstr($data, '.')) {
            throw new InvalidArgumentException('setData(): An association is expected.');
        }

        $fields = explode('.', $data);
        $this->data = $fields[0];

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'gallery';
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('relative_path'));
        $resolver->setRequired(array('imagine_filter'));

        $resolver->setDefaults(array(
            'class' => '',
            'padding' => '',
            'name' => '',
            'orderable' => false,
            'searchable' => false,
            'title' => '',
            'type' => '',
            'visible' => true,
            'width' => '',
            'search_type' => 'like',
            'filter_type' => 'text',
            'filter_options' => array(),
            'filter_property' => '',
            'filter_search_column' => '',
            'holder_url' => '',
            'holder_width' => '50',
            'holder_height' => '50',
            'enlarge' => false
        ));

        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('padding', 'string');
        $resolver->setAllowedTypes('name', 'string');
        $resolver->setAllowedTypes('orderable', 'bool');
        $resolver->setAllowedTypes('searchable', 'bool');
        $resolver->setAllowedTypes('title', 'string');
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('visible', 'bool');
        $resolver->setAllowedTypes('width', 'string');
        $resolver->setAllowedTypes('search_type', 'string');
        $resolver->setAllowedTypes('filter_type', 'string');
        $resolver->setAllowedTypes('filter_options', 'array');
        $resolver->setAllowedTypes('filter_property', 'string');
        $resolver->setAllowedTypes('filter_search_column', 'string');
        $resolver->setAllowedTypes('imagine_filter', 'string');
        $resolver->setAllowedTypes('relative_path', 'string');
        $resolver->setAllowedTypes('holder_url', 'string');
        $resolver->setAllowedTypes('holder_width', 'string');
        $resolver->setAllowedTypes('holder_height', 'string');
        $resolver->setAllowedTypes('enlarge', 'bool');

        $resolver->setAllowedValues('search_type', array('like', 'notLike', 'eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'in', 'notIn', 'isNull', 'isNotNull'));
        $resolver->setAllowedValues('filter_type', array('text', 'select'));

        return $this;
    }
}
