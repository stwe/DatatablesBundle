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
            'placeholder_text' => null
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
    // Helper
    //-------------------------------------------------

    /**
     * Get a condition.
     *
     * @param Andx         $andExpr
     * @param QueryBuilder $pivot
     * @param string       $searchField
     * @param mixed        $searchValue
     * @param integer      $i
     *
     * @return Andx
     */
    protected function getAndExpression(Andx $andExpr, QueryBuilder $pivot, $searchField, $searchValue, $i)
    {
        switch ($this->searchType) {
            case 'like':
                $andExpr->add($pivot->expr()->like($searchField, '?' . $i));
                $pivot->setParameter($i, '%' . $searchValue . '%');
                break;
            case 'notLike':
                $andExpr->add($pivot->expr()->notLike($searchField, '?' . $i));
                $pivot->setParameter($i, '%' . $searchValue . '%');
                break;
            case 'eq':
                $andExpr->add($pivot->expr()->eq($searchField, '?' . $i));
                $pivot->setParameter($i, $searchValue);
                break;
            case 'neq':
                $andExpr->add($pivot->expr()->neq($searchField, '?' . $i));
                $pivot->setParameter($i, $searchValue);
                break;
            case 'lt':
                $andExpr->add($pivot->expr()->lt($searchField, '?' . $i));
                $pivot->setParameter($i, $searchValue);
                break;
            case 'lte':
                $andExpr->add($pivot->expr()->lte($searchField, '?' . $i));
                $pivot->setParameter($i, $searchValue);
                break;
            case 'gt':
                $andExpr->add($pivot->expr()->gt($searchField, '?' . $i));
                $pivot->setParameter($i, $searchValue);
                break;
            case 'gte':
                $andExpr->add($pivot->expr()->gte($searchField, '?' . $i));
                $pivot->setParameter($i, $searchValue);
                break;
            case 'in':
                $andExpr->add($pivot->expr()->in($searchField, '?' . $i));
                $pivot->setParameter($i, explode(',', $searchValue));
                break;
            case 'notIn':
                $andExpr->add($pivot->expr()->notIn($searchField, '?' . $i));
                $pivot->setParameter($i, explode(',', $searchValue));
                break;
            case 'isNull':
                $andExpr->add($pivot->expr()->isNull($searchField));
                break;
            case 'isNotNull':
                $andExpr->add($pivot->expr()->isNotNull($searchField));
                break;
        }

        return $andExpr;
    }

    /**
     * @param Andx         $andExpr
     * @param QueryBuilder $pivot
     * @param string       $searchField
     * @param mixed        $from
     * @param mixed        $to
     * @param integer      $i
     *
     * @return Andx
     */
    protected function getBetweenAndExpression(Andx $andExpr, QueryBuilder $pivot, $searchField, $from, $to, $i)
    {
        $k = $i + 1;
        $andExpr->add($pivot->expr()->between($searchField, '?' . $i, '?' . $k));
        $pivot->setParameter($i, $from);
        $pivot->setParameter($k, $to);

        return $andExpr;
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
}
