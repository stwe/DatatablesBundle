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
 * Class ArrayColumn
 *
 * @package Wg\UniversalDataTable\DataTable\Column
 */
class ArrayColumn extends Column
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

        $this->data = $data;

        return $this;
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

        $resolver->setRequired(array('data'));
        $resolver->addAllowedTypes('data', 'string');

        return $this;
    }
}
