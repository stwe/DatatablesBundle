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

use Sg\DatatablesBundle\Datatable\Filter\TextFilter;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Column
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class Column extends AbstractColumn
{
    /**
     * The Column is editable.
     */
    use EditableTrait;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:column:column.html.twig';
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

        $resolver->setDefaults(array(
            'filter' => array(TextFilter::class, array()),
            'editable' => false,
            'editable_if' => null,
        ));

        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('editable', 'bool');
        $resolver->setAllowedTypes('editable_if', array('null', 'Closure'));

        return $this;
    }
}
