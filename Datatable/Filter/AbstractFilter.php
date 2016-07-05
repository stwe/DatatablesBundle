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

use Sg\DatatablesBundle\Datatable\View\AbstractViewOptions;
use Sg\DatatablesBundle\OptionsResolver\OptionsInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\QueryBuilder;
use Doctrine\ORM\Query\Expr\Andx;

/**
 * Class AbstractFilter
 *
 * @package Sg\DatatablesBundle\Datatable\Filter
 */
abstract class AbstractFilter implements FilterInterface, OptionsInterface
{
    /**
     * Filter options.
     *
     * @var array
     */
    protected $options;

    /**
     * The search type (e.g. 'like').
     *
     * @var string
     */
    protected $searchType;

    /**
     * Filter property: Column name, on which the filter is applied,
     * based on options for this column.
     *
     * @var string
     */
    protected $property;

    /**
     * Implementation of the searchCol config property of jquery datatable.
     *
     * @var string
     */
    protected $searchColumn;

    /**
     * @var string
     */
    protected $class;

    /**
     * @var boolean
     */
    protected $cancelButton;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->options = array();
    }

    //-------------------------------------------------
    // OptionsResolver
    //-------------------------------------------------

    /**
     * Setup options resolver.
     *
     * @param array $options
     *
     * @return $this
     * @throws \Exception
     */
    public function setupOptionsResolver(array $options)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);

        AbstractViewOptions::callingSettersWithOptions($this->options, $this);

        return $this;
    }

    //-------------------------------------------------
    // FilterInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getProperty()
    {
        return $this->property;
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
        switch ($this->getSearchType()) {
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
     * Get search type.
     *
     * @return string
     */
    public function getSearchType()
    {
        return $this->searchType;
    }

    /**
     * Set search type.
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
     * Set property.
     *
     * @param string $property
     *
     * @return $this
     */
    public function setProperty($property)
    {
        $this->property = $property;

        return $this;
    }

    /**
     * Get search column.
     *
     * @return string
     */
    public function getSearchColumn()
    {
        return $this->searchColumn;
    }

    /**
     * Set search column.
     *
     * @param string $searchColumn
     *
     * @return $this
     */
    public function setSearchColumn($searchColumn)
    {
        $this->searchColumn = $searchColumn;

        return $this;
    }

    /**
     * Get class.
     *
     * @return string
     */
    public function getClass()
    {
        return $this->class;
    }

    /**
     * Set class.
     *
     * @param string $class
     *
     * @return $this
     */
    public function setClass($class)
    {
        $this->class = $class;

        return $this;
    }

    /**
     * Get cancel button.
     *
     * @return boolean
     */
    public function getCancelButton()
    {
        return $this->cancelButton;
    }

    /**
     * Set cancel button.
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
}
