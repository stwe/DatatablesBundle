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

use Sg\DatatablesBundle\Datatable\OptionsTrait;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Andx;

/**
 * Class AbstractFilter
 *
 * @package Sg\DatatablesBundle\Datatable\Filter
 */
abstract class AbstractFilter implements FilterInterface
{
    /**
     * Use the OptionsResolver.
     */
    use OptionsTrait;

    /**
     * The search type (e.g. 'like').
     * Default: 'like'
     *
     * @var string
     */
    protected $searchType;

    /**
     * Column name, on which the filter is applied, based on options for this column.
     * Default: null
     *
     * @var null|string
     */
    protected $searchColumn;

    /**
     * Define an initial search (same as DataTables 'searchCols' option).
     * Default: null
     *
     * @var null|string
     */
    protected $initialSearch;

    /**
     * Additional classes for the html filter element.
     * Default: null
     *
     * @var null|string
     */
    protected $classes;

    /**
     * Renders a Cancel-Button to reset the filter.
     * Default: false
     *
     * @var bool
     */
    protected $cancelButton;

    /**
     * Specifies whether a placeholder is displayed.
     * Default: true
     *
     * @var bool
     */
    protected $placeholder;

    /**
     * The placeholder text.
     * Default: null (The Column Title is used.)
     *
     * @var null|string
     */
    protected $placeholderText;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * AbstractFilter constructor.
     */
    public function __construct()
    {
        $this->initOptions();
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
        $resolver->setDefaults(array(
            'search_type' => 'like',
            'search_column' => null,
            'initial_search' => null,
            'classes' => null,
            'cancel_button' => false,
            'placeholder' => true,
            'placeholder_text' => null,
        ));

        $resolver->setAllowedTypes('search_type', 'string');
        $resolver->setAllowedTypes('search_column', array('null', 'string'));
        $resolver->setAllowedTypes('initial_search', array('null', 'string'));
        $resolver->setAllowedTypes('classes', array('null', 'string'));
        $resolver->setAllowedTypes('cancel_button', 'bool');
        $resolver->setAllowedTypes('placeholder', 'bool');
        $resolver->setAllowedTypes('placeholder_text', array('null', 'string'));

        $resolver->setAllowedValues('search_type', array('like', 'notLike', 'eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'in', 'notIn', 'isNull', 'isNotNull'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get searchType.
     *
     * @return string
     */
    public function getSearchType()
    {
        return $this->searchType;
    }

    /**
     * Set searchType.
     *
     * @param string $searchType
     *
     * @return $this
     */
    public function setSearchType($searchType)
    {
        $this->searchType = $searchType;

        return $this;
    }

    /**
     * Get searchColumn.
     *
     * @return null|string
     */
    public function getSearchColumn()
    {
        return $this->searchColumn;
    }

    /**
     * Set searchColumn.
     *
     * @param null|string $searchColumn
     *
     * @return $this
     */
    public function setSearchColumn($searchColumn)
    {
        $this->searchColumn = $searchColumn;

        return $this;
    }

    /**
     * Get initialSearch.
     *
     * @return null|string
     */
    public function getInitialSearch()
    {
        return $this->initialSearch;
    }

    /**
     * Set initialSearch.
     *
     * @param null|string $initialSearch
     *
     * @return $this
     */
    public function setInitialSearch($initialSearch)
    {
        $this->initialSearch = $initialSearch;

        return $this;
    }

    /**
     * Get classes.
     *
     * @return null|string
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * Set classes.
     *
     * @param null|string $classes
     *
     * @return $this
     */
    public function setClasses($classes)
    {
        $this->classes = $classes;

        return $this;
    }

    /**
     * Get cancelButton.
     *
     * @return boolean
     */
    public function isCancelButton()
    {
        return $this->cancelButton;
    }

    /**
     * Set cancelButton.
     *
     * @param boolean $cancelButton
     *
     * @return $this
     */
    public function setCancelButton($cancelButton)
    {
        $this->cancelButton = $cancelButton;

        return $this;
    }

    /**
     * Get placeholder.
     *
     * @return boolean
     */
    public function isPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * Set placeholder.
     *
     * @param boolean $placeholder
     *
     * @return $this
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * Get placeholderText.
     *
     * @return null|string
     */
    public function getPlaceholderText()
    {
        return $this->placeholderText;
    }

    /**
     * Set placeholderText.
     *
     * @param null|string $placeholderText
     *
     * @return $this
     */
    public function setPlaceholderText($placeholderText)
    {
        $this->placeholderText = $placeholderText;

        return $this;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Get an andExpression.
     *
     * @param Andx         $andExpr
     * @param QueryBuilder $qb
     * @param string       $searchField
     * @param mixed        $searchValue
     * @param int          $parameterCounter
     *
     * @return Andx
     */
    protected function getAndExpression(Andx $andExpr, QueryBuilder $qb, $searchField, $searchValue, $parameterCounter)
    {
        switch ($this->searchType) {
            case 'like':
                $andExpr->add($qb->expr()->like($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, '%'.$searchValue.'%');
                break;
            case 'notLike':
                $andExpr->add($qb->expr()->notLike($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, '%'.$searchValue.'%');
                break;
            case 'eq':
                $andExpr->add($qb->expr()->eq($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, $searchValue);
                break;
            case 'neq':
                $andExpr->add($qb->expr()->neq($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, $searchValue);
                break;
            case 'lt':
                $andExpr->add($qb->expr()->lt($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, $searchValue);
                break;
            case 'lte':
                $andExpr->add($qb->expr()->lte($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, $searchValue);
                break;
            case 'gt':
                $andExpr->add($qb->expr()->gt($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, $searchValue);
                break;
            case 'gte':
                $andExpr->add($qb->expr()->gte($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, $searchValue);
                break;
            case 'in':
                $andExpr->add($qb->expr()->in($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, explode(',', $searchValue));
                break;
            case 'notIn':
                $andExpr->add($qb->expr()->notIn($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, explode(',', $searchValue));
                break;
            case 'isNull':
                $andExpr->add($qb->expr()->isNull($searchField));
                break;
            case 'isNotNull':
                $andExpr->add($qb->expr()->isNotNull($searchField));
                break;
        }

        return $andExpr;
    }

    /**
     * Get a betweenAndExpression.
     *
     * @param Andx         $andExpr
     * @param QueryBuilder $qb
     * @param string       $searchField
     * @param mixed        $from
     * @param mixed        $to
     * @param int          $parameterCounter
     *
     * @return Andx
     */
    protected function getBetweenAndExpression(Andx $andExpr, QueryBuilder $qb, $searchField, $from, $to, $parameterCounter)
    {
        $k = $parameterCounter + 1;
        $andExpr->add($qb->expr()->between($searchField, '?'.$parameterCounter, '?'.$k));
        $qb->setParameter($parameterCounter, $from);
        $qb->setParameter($k, $to);

        return $andExpr;
    }
}
