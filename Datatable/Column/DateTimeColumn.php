<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author Tomáš Polívka <draczris@gmail.com>
 * @author stwe
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class DateTimeColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class DateTimeColumn extends AbstractDataRenderColumn
{
    /**
     * DateTime format string.
     *
     * @link http://momentjs.com/
     *
     * @var string
     */
    private $dateFormat;


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        parent::setDefaults();

        $this->setRender("render_datetime");

        $this->setDateFormat("lll");

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);

        if (array_key_exists("format", $options)) {
            $this->setDateFormat($options["format"]);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return "SgDatatablesBundle:Column:datetime.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return "datetime";
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
            throw new InvalidArgumentException("setDateFormat(): String expected.");
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
