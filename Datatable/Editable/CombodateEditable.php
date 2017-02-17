<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Editable;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CombodateEditable
 *
 * @package Sg\DatatablesBundle\Datatable\Editable
 */
class CombodateEditable extends AbstractEditable
{
    /**
     * Format used for sending value to server.
     *
     * @var string
     */
    protected $format;

    /**
     * Format used for displaying date. If not specified equals to $format.
     *
     * @var null|string
     */
    protected $viewFormat;

    /*
    @todo
    protected $combodate;
    */

    //-------------------------------------------------
    // FilterInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'combodate';
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

        $resolver->setDefaults(array(
            'format' => 'YYYY-MM-DD',
            'view_format' => null,
        ));

        $resolver->setAllowedTypes('format', 'string');
        $resolver->setAllowedTypes('view_format', array('string', 'null'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get format.
     *
     * @return string
     */
    public function getFormat()
    {
        return $this->format;
    }

    /**
     * Set format.
     *
     * @param string $format
     *
     * @return $this
     */
    public function setFormat($format)
    {
        $this->format = $format;

        return $this;
    }

    /**
     * Get viewFormat.
     *
     * @return null|string
     */
    public function getViewFormat()
    {
        return $this->viewFormat;
    }

    /**
     * Set viewFormat.
     *
     * @param null|string $viewFormat
     *
     * @return $this
     */
    public function setViewFormat($viewFormat)
    {
        $this->viewFormat = $viewFormat;

        return $this;
    }
}
