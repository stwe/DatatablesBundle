<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Filter;

use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\Query\Expr\Composite;
use Doctrine\ORM\Query\Expr\Orx;
use Doctrine\ORM\QueryBuilder;
use Sg\DatatablesBundle\Datatable\OptionsTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class AbstractFilter implements FilterInterface
{
    use OptionsTrait;

    /**
     * The search type (e.g. 'like').
     * Default: 'like'.
     *
     * @var string
     */
    protected $searchType;

    /**
     * Column name, on which the filter is applied, based on options for this column.
     * Default: null.
     *
     * @var string|null
     */
    protected $searchColumn;

    /**
     * Define an initial search (same as DataTables 'searchCols' option).
     * Default: null.
     *
     * @var string|null
     */
    protected $initialSearch;

    /**
     * Additional classes for the html filter element.
     * Default: null.
     *
     * @var string|null
     */
    protected $classes;

    /**
     * Renders a Cancel-Button to reset the filter.
     * Default: false.
     *
     * @var bool
     */
    protected $cancelButton;

    /**
     * Specifies whether a placeholder is displayed.
     * Default: true.
     *
     * @var bool
     */
    protected $placeholder;

    /**
     * The placeholder text.
     * Default: null (The Column Title is used.).
     *
     * @var string|null
     */
    protected $placeholderText;

    public function __construct()
    {
        $this->initOptions();
    }

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'search_type' => 'like',
            'search_column' => null,
            'initial_search' => null,
            'classes' => null,
            'cancel_button' => false,
            'placeholder' => true,
            'placeholder_text' => null,
        ]);

        $resolver->setAllowedTypes('search_type', 'string');
        $resolver->setAllowedTypes('search_column', ['null', 'string']);
        $resolver->setAllowedTypes('initial_search', ['null', 'string']);
        $resolver->setAllowedTypes('classes', ['null', 'string']);
        $resolver->setAllowedTypes('cancel_button', 'bool');
        $resolver->setAllowedTypes('placeholder', 'bool');
        $resolver->setAllowedTypes('placeholder_text', ['null', 'string']);

        $resolver->setAllowedValues('search_type', ['like', '%like', 'like%', 'notLike', 'eq', 'neq', 'lt', 'lte', 'gt', 'gte', 'in', 'notIn', 'isNull', 'isNotNull']);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return string
     */
    public function getSearchType()
    {
        return $this->searchType;
    }

    /**
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

    /**
     * @return string|null
     */
    public function getInitialSearch()
    {
        return $this->initialSearch;
    }

    /**
     * @param string|null $initialSearch
     *
     * @return $this
     */
    public function setInitialSearch($initialSearch)
    {
        $this->initialSearch = $initialSearch;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getClasses()
    {
        return $this->classes;
    }

    /**
     * @param string|null $classes
     *
     * @return $this
     */
    public function setClasses($classes)
    {
        $this->classes = $classes;

        return $this;
    }

    /**
     * @return bool
     */
    public function isCancelButton()
    {
        return $this->cancelButton;
    }

    /**
     * @param bool $cancelButton
     *
     * @return $this
     */
    public function setCancelButton($cancelButton)
    {
        $this->cancelButton = $cancelButton;

        return $this;
    }

    /**
     * @return bool
     */
    public function isPlaceholder()
    {
        return $this->placeholder;
    }

    /**
     * @param bool $placeholder
     *
     * @return $this
     */
    public function setPlaceholder($placeholder)
    {
        $this->placeholder = $placeholder;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPlaceholderText()
    {
        return $this->placeholderText;
    }

    /**
     * @param string|null $placeholderText
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
     * Add an or condition.
     *
     * @param string $searchType
     * @param string $searchField
     * @param string $searchTypeOfField
     * @param int    $parameterCounter
     *
     * @return Composite
     */
    public function addOrExpression(Orx $orExpr, QueryBuilder $qb, $searchType, $searchField, $searchValue, $searchTypeOfField, &$parameterCounter)
    {
        return $this->getExpression($orExpr, $qb, $searchType, $searchField, $searchValue, $searchTypeOfField, $parameterCounter);
    }

    /**
     * Get an expression.
     *
     * @param string $searchType
     * @param string $searchField
     * @param string $searchTypeOfField
     * @param int    $parameterCounter
     *
     * @return Composite
     */
    protected function getExpression(Composite $expr, QueryBuilder $qb, $searchType, $searchField, $searchValue, $searchTypeOfField, &$parameterCounter)
    {
        // Prevent doctrine issue with "?0" (https://github.com/doctrine/doctrine2/issues/6699)
        ++$parameterCounter;

        // Only StringExpression can be searched with LIKE (https://github.com/doctrine/doctrine2/issues/6363)
        if (
            // Not a StringExpression
            ! preg_match('/text|string|date|time|array|json_array|simple_array/', $searchTypeOfField)
            // Subqueries can't be search with LIKE
            || preg_match('/SELECT.+FROM.+/is', $searchField)
            // CASE WHEN can't be search with LIKE
            || preg_match('/CASE.+WHEN.+END/is', $searchField)
        ) {
            switch ($searchType) {
                case 'like':
                case '%like':
                case 'like%':
                    $searchType = 'eq';

                    break;
                case 'notLike':
                    $searchType = 'neq';

                    break;
            }
        }

        // Skip search on columns when column type don't match search value type
        // (Prevent converting data type errors)
        $incompatibleTypeOfField = false;
        switch ($searchTypeOfField) {
            case 'decimal':
            case 'float':
                if (is_numeric($searchValue)) {
                    $searchValue = (float) $searchValue;
                } else {
                    $incompatibleTypeOfField = true;
                }

                break;
            case 'integer':
            case 'bigint':
            case 'smallint':
            case 'boolean':
                if ($searchValue === (string) (int) $searchValue) {
                    $searchValue = (int) $searchValue;
                } else {
                    $incompatibleTypeOfField = true;
                }

                break;
        }
        if ($incompatibleTypeOfField) {
            return
                $expr instanceof Andx
                    // No result found
                    ? $expr->add($qb->expr()->eq(1, 0))
                    // Column skipped from search
                    : $expr
                ;
        }

        switch ($searchType) {
            case 'like':
                $expr->add($qb->expr()->like($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, '%'.$searchValue.'%');

                break;
            case '%like':
                $expr->add($qb->expr()->like($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, '%'.$searchValue);

                break;
            case 'like%':
                $expr->add($qb->expr()->like($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, $searchValue.'%');

                break;
            case 'notLike':
                $expr->add($qb->expr()->notLike($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, '%'.$searchValue.'%');

                break;
            case 'eq':
                $expr->add($qb->expr()->eq($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, $searchValue);

                break;
            case 'neq':
                $expr->add($qb->expr()->neq($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, $searchValue);

                break;
            case 'lt':
                $expr->add($qb->expr()->lt($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, $searchValue);

                break;
            case 'lte':
                $expr->add($qb->expr()->lte($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, $searchValue);

                break;
            case 'gt':
                $expr->add($qb->expr()->gt($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, $searchValue);

                break;
            case 'gte':
                $expr->add($qb->expr()->gte($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, $searchValue);

                break;
            case 'in':
                $expr->add($qb->expr()->in($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, explode(',', $searchValue));

                break;
            case 'notIn':
                $expr->add($qb->expr()->notIn($searchField, '?'.$parameterCounter));
                $qb->setParameter($parameterCounter, explode(',', $searchValue));

                break;
            case 'isNull':
                $expr->add($qb->expr()->isNull($searchField));

                break;
            case 'isNotNull':
                $expr->add($qb->expr()->isNotNull($searchField));

                break;
        }

        return $expr;
    }

    /**
     * Get a betweenAndExpression.
     *
     * @param string $searchField
     * @param int    $parameterCounter
     *
     * @return Andx
     */
    protected function getBetweenAndExpression(Andx $andExpr, QueryBuilder $qb, $searchField, $from, $to, $parameterCounter)
    {
        ++$parameterCounter;

        $k = $parameterCounter + 1;
        $andExpr->add($qb->expr()->between($searchField, '?'.$parameterCounter, '?'.$k));
        $qb->setParameter($parameterCounter, $from);
        $qb->setParameter($k, $to);

        return $andExpr;
    }
}
