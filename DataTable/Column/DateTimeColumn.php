<?php

/**
 * This file is part of the WgUniversalDataTableBundle package.
 *
 * (c) stwe <https://github.com/stwe/DataTablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Tomáš Polívka <draczris@gmail.com>
 * @author stwe
 */

namespace Wg\UniversalDataTable\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class DateTimeColumn
 *
 * @package Wg\UniversalDataTable\DataTable\Column
 */
class DateTimeColumn extends TimeagoColumn
{
    /**
     * DateTime format string.
     *
     * @link http://momentjs.com/
     *
     * @var string
     */
    protected $dateFormat;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'WgUniversalDataTableBundle:Column:datetime.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'datetime';
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'render' => 'render_datetime',
            "date_format" => 'lll',
        ));

        $resolver->addAllowedTypes('date_format', 'string');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set date format.
     *
     * @param string $dateFormat
     *
     * @return $this
     */
    public function setDateFormat($dateFormat)
    {
        if (empty($dateFormat) || !is_string($dateFormat)) {
            throw new InvalidArgumentException('setDateFormat(): Expecting non-empty string.');
        }

        $this->dateFormat = $dateFormat;

        return $this;
    }

    /**
     * Get date format.
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }
}
