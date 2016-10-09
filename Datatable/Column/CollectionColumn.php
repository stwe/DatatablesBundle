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

/**
 * Class CollectionColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class CollectionColumn extends Column
{
    /**
     * Count elements.
     *
     * @var bool
     */
    protected $count;

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

        $resolver->setRequired('data');

        $resolver->setDefaults(array(
            'count' => false,
        ));

        $resolver->setAllowedTypes('data', 'string');
        $resolver->setAllowedTypes('count', 'bool');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get count.
     *
     * @return bool
     */
    public function isCount()
    {
        return $this->count;
    }

    /**
     * Set count.
     *
     * @param bool $count
     *
     * @return $this
     */
    public function setCount($count)
    {
        $this->count = $count;

        return $this;
    }
}
