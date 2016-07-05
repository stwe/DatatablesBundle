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
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Andx;

/**
 * Class TextFilter
 *
 * @package Sg\DatatablesBundle\Datatable\Filter
 */
class TextFilter extends AbstractFilter
{
    //-------------------------------------------------
    // FilterInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Filters:filter_text.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function addAndExpression(Andx $andExpr, QueryBuilder $pivot, $searchField, $searchValue, &$i)
    {
        $andExpr = $this->getAndExpression($andExpr, $pivot, $searchField, $searchValue, $i);
        $i++;

        return $andExpr;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'text';
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
            'search_type' => 'like',
            'property' => '',
            'search_column' => '',
            'class' => '',
            'cancel_button' => false
        ));

        $resolver->setAllowedTypes('search_type', 'string');
        $resolver->setAllowedTypes('property', 'string');
        $resolver->setAllowedTypes('search_column', 'string');
        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('cancel_button', 'bool');

        $resolver->setAllowedValues('search_type', array('like', 'notLike', 'eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'in', 'notIn', 'isNull', 'isNotNull'));

        return $this;
    }
}
