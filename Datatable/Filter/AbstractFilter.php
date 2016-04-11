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
     * Get property.
     *
     * @return string
     */
    public function getProperty()
    {
        return $this->property;
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
}
