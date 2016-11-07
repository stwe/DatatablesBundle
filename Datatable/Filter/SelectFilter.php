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
use Exception;

/**
 * Class SelectFilter
 *
 * @package Sg\DatatablesBundle\Datatable\Filter
 */
class SelectFilter extends TextFilter
{
    /**
     * This allows to define a search type (e.g. 'like' or 'isNull') for each item in 'selectOptions'.
     * Default: array() - The default value of searchType is used.
     *
     * @var array
     */
    protected $selectSearchTypes;

    /**
     * Select options for this filter type (e.g. for boolean column: '1' => 'Yes', '0' => 'No').
     * Default: array()
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
        return 'SgDatatablesBundle:filter:select.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function addAndExpression(Andx $andExpr, QueryBuilder $qb, $searchField, $searchValue, &$parameterCounter)
    {
        $searchTypesCount = count($this->selectSearchTypes);

        if ($searchTypesCount > 0 && $searchTypesCount === count($this->selectOptions)) {
            $this->searchType = $this->selectSearchTypes[$searchValue];
        } else {
            throw new Exception('SelectFilter::addAndExpression(): The search types array is not valid.');
        }

        $andExpr = $this->getAndExpression($andExpr, $qb, $searchField, $searchValue, $parameterCounter);
        $parameterCounter++;

        return $andExpr;
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

        // placeholder is not a valid attribute on a <select> input
        $resolver->remove('placeholder');
        $resolver->remove('placeholder_text');

        $resolver->setDefaults(array(
            'select_search_types' => array(),
            'select_options' => array(),
        ));

        $resolver->setAllowedTypes('select_search_types', 'array');
        $resolver->setAllowedTypes('select_options', 'array');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get selectSearchTypes.
     *
     * @return array
     */
    public function getSelectSearchTypes()
    {
        return $this->selectSearchTypes;
    }

    /**
     * Set selectSearchTypes.
     *
     * @param array $selectSearchTypes
     *
     * @return $this
     */
    public function setSelectSearchTypes(array $selectSearchTypes)
    {
        $this->selectSearchTypes = $selectSearchTypes;

        return $this;
    }

    /**
     * Get selectOptions.
     *
     * @return array
     */
    public function getSelectOptions()
    {
        return $this->selectOptions;
    }

    /**
     * Set selectOptions.
     *
     * @param array $selectOptions
     *
     * @return $this
     */
    public function setSelectOptions(array $selectOptions)
    {
        $this->selectOptions = $selectOptions;

        return $this;
    }
}
