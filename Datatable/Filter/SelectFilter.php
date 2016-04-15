<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Filter;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class SelectFilter
 *
 * @package Sg\DatatablesBundle\Datatable\Filter
 */
class SelectFilter extends TextFilter
{
    /**
     * Select options for this filter type (e.g. for boolean column: '1' => 'Yes', '0' => 'No').
     *
     * @var array
     */
    protected $selectOptions;

    //-------------------------------------------------
    // FilterInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Filters:filter_select.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'select';
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

        $resolver->setDefaults(array(
            'select_options' => array(),
        ));

        $resolver->setAllowedTypes('select_options', 'array');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get select options.
     *
     * @return array
     */
    public function getSelectOptions()
    {
        return $this->selectOptions;
    }

    /**
     * Set select options.
     *
     * @param array $selectOptions
     *
     * @return $this
     */
    public function setSelectOptions($selectOptions)
    {
        $this->selectOptions = $selectOptions;

        return $this;
    }
}
