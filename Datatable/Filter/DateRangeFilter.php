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

use DateTime;
use Doctrine\ORM\Query\Expr\Andx;
use Doctrine\ORM\QueryBuilder;
use \IntlDateFormatter;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class DateRangeFilter
 *
 * @package Sg\DatatablesBundle\Datatable\Filter
 */
class DateRangeFilter extends AbstractFilter
{
    protected $locale;

    //-------------------------------------------------
    // FilterInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:filter:daterange.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function addAndExpression(Andx $andExpr, QueryBuilder $qb, $searchField, $searchValue, &$parameterCounter)
    {
        list($_dateStart, $_dateEnd) = explode(' - ', $searchValue);
        $dateStart = $this->getDateTime($_dateStart);
        $dateEnd = $this->getDateTime($_dateEnd);
        $dateEnd->setTime(23, 59, 59);

        $andExpr = $this->getBetweenAndExpression($andExpr, $qb, $searchField, $dateStart->format('Y-m-d H:i:s'), $dateEnd->format('Y-m-d H:i:s'), $parameterCounter);
        $parameterCounter += 2;

        return $andExpr;
    }

    /**
     * @param string $date
     * @return DateTime
     */
    protected function getDateTime($date) : \DateTime
    {
        $dateFormatter = IntlDateFormatter::create(
            $this->locale, 
            IntlDateFormatter::SHORT, 
            IntlDateFormatter::NONE
        );
        $timestamp = $dateFormatter->parse($date);
        
        return new \DateTime('@' . $timestamp);
    }

    /**
     * @return string
     */
    public function getLocale()
    {
        return $this->locale;
    }

    /**
     * @param string $locale
     * @return $this
     */
    public function setLocale($locale)  
    {
        $this->locale = $locale;
        
        return $this;
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
        
        $resolver->setDefault('locale', 'en');
        $resolver->setAllowedTypes('locale', 'string');
        $resolver->remove('search_type');

        return $this;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Returns the type for the <input> element.
     *
     * @return string
     */
    public function getType()
    {
        return 'text';
    }
}
