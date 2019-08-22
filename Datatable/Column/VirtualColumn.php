<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Exception;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VirtualColumn extends Column
{
    /**
     * Order field.
     *
     * @var string|null
     */
    protected $orderColumn;

    /**
     * Search field.
     *
     * @var string|null
     */
    protected $searchColumn;

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->remove('data');
        $resolver->remove('join_type');
        $resolver->remove('editable');

        $resolver->setDefaults([
            'orderable' => false,
            'searchable' => false,
            'order_column' => null,
            'search_column' => null,
        ]);

        $resolver->setAllowedTypes('order_column', ['null', 'string']);
        $resolver->setAllowedTypes('search_column', ['null', 'string']);

        $resolver->setNormalizer('orderable', function (Options $options, $value) {
            if (null === $options['order_column'] && true === $value) {
                throw new Exception('VirtualColumn::configureOptions(): For the orderable option, order_column should not be null.');
            }

            return $value;
        });

        $resolver->setNormalizer('searchable', function (Options $options, $value) {
            if (null === $options['search_column'] && true === $value) {
                throw new Exception('VirtualColumn::configureOptions(): For the searchable option, search_column should not be null.');
            }

            return $value;
        });

        return $this;
    }

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

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
    public function getColumnType()
    {
        return parent::VIRTUAL_COLUMN;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return string|null
     */
    public function getOrderColumn()
    {
        return $this->orderColumn;
    }

    /**
     * @param string|null $orderColumn
     *
     * @return $this
     */
    public function setOrderColumn($orderColumn)
    {
        $this->orderColumn = $orderColumn;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSearchColumn()
    {
        return $this->searchColumn;
    }

    /**
     * @param string|null $searchColumn
     *
     * @return $this
     */
    public function setSearchColumn($searchColumn)
    {
        $this->searchColumn = $searchColumn;

        return $this;
    }
}
