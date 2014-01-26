<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Column;

use Sg\DatatablesBundle\Column\AbstractColumn as BaseColumn;

use Exception;

/**
 * Class DateTimeColumn
 *
 * @package Sg\DatatablesBundle\Column
 */
class DateTimeColumn extends BaseColumn
{
    /**
     * DateTime formatting token based on locale.
     *
     * There are a few tokens that can be used to format a moment based on its language:
     * @link http://momentjs.com/docs/
     *
     * @var string
     */
    private $localizedFormat;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param null|string $property An entity's property
     *
     * @throws Exception
     */
    public function __construct($property = null)
    {
        if (null == $property) {
            throw new Exception("The entity's property can not be null.");
        }

        parent::__construct($property);
    }


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'datetime';
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        if (array_key_exists('render', $options)) {
            if (null == $options['render']) {
                throw new Exception('The render option can not be null.');
            }
        }

        if (array_key_exists('format', $options)) {
            if (null == $options['format']) {
                throw new Exception('The format option can not be null.');
            } else {
                $this->setLocalizedFormat($options['format']);
            }
        }

        parent::setOptions($options);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        parent::setDefaults();

        $this->setMRender('render_datetime');
        $this->setLocalizedFormat('lll');
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set localized format.
     *
     * @param string $localizedFormat
     *
     * @return $this
     * @throws Exception
     */
    public function setLocalizedFormat($localizedFormat)
    {
        $localizedFormats = array('LT', 'L', 'l', 'LL', 'll', 'LLL', 'lll', 'LLLL', 'llll');

        if (in_array($localizedFormat, $localizedFormats, true)) {
            $this->localizedFormat = $localizedFormat;
        } else {
            throw new Exception("The localized format {$localizedFormat} is not supported.");
        }

        return $this;
    }

    /**
     * Get localized format.
     *
     * @return string
     */
    public function getLocalizedFormat()
    {
        return $this->localizedFormat;
    }
}