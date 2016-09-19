<?php
/**
 * Created by PhpStorm.
 * User: nima
 * Date: 15.09.2016
 * Time: 17:15
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
class ComparisonFilter extends AbstractFilter
{
    //-------------------------------------------------
    // FilterInterface
    //-------------------------------------------------
    protected $selectOptions;

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:Filters:filter_comparison.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function addAndExpression(Andx $andExpr, QueryBuilder $pivot, $searchField, $searchValue, &$i)
    {

        if ($searchValue[0] == ">" || $searchValue[0] == "<" || $searchValue[0] == "=") {
            $operator = $searchValue[0];
            $searchValue = substr($searchValue, 1);
            switch ($operator) {
                case '=':
                    $andExpr->add($pivot->expr()->eq($searchField, '?' . $i));
                    $pivot->setParameter($i, $searchValue);
                    break;
                case '>':
                    $andExpr->add($pivot->expr()->gt($searchField, '?' . $i));
                    $pivot->setParameter($i, $searchValue);
                    break;
                case '<':
                    $andExpr->add($pivot->expr()->lt($searchField, '?' . $i));
                    $pivot->setParameter($i, $searchValue);
                    break;
            }
        } else {
            $andExpr->add($pivot->expr()->like($searchField, '?' . $i));
            $pivot->setParameter($i, '%' . $searchValue . '%');
        }

        $i++;
        return $andExpr;

    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'comparison';
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
            'cancel_button' => false,
            'select_options' => array(),

        ));

        $resolver->setAllowedTypes('search_type', 'string');
        $resolver->setAllowedTypes('property', 'string');
        $resolver->setAllowedTypes('search_column', 'string');
        $resolver->setAllowedTypes('class', 'string');
        $resolver->setAllowedTypes('cancel_button', 'bool');
        $resolver->setAllowedTypes('select_options', 'array');

        $resolver->setAllowedValues('search_type', array('like', 'eq', 'lt', 'gt'));

        return $this;
    }

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
