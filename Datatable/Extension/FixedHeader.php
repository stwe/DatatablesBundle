<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Extension;

use Exception;
use Sg\DatatablesBundle\Datatable\OptionsTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FixedHeader
{
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - FixedHeader Extension
    //-------------------------------------------------

    /**
     * Enable / disable fixed footer
     * Enable the fixing of the table footer (true) or disable (false).
     * Required option.
     *
     * @var bool
     */
    protected $footer;

    /**
     * Offset the table's fixed footer
     * Set the offset (in pixels) of the footer element's offset for the scrolling calculations.
     * Optional.
     *
     * @var int
     */
    protected $footerOffset;

    /**
     * Enable / disable fixed header
     * Enable the fixing of the table header (true) or disable (false).
     * Optional.
     *
     * @var bool
     */
    protected $header;

    /**
     * Offset the table's fixed header
     * Set the offset (in pixels) of the header element's offset for the scrolling calculations.
     * Optional.
     *
     * @var int
     */
    protected $headerOffset;

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
        $resolver->setDefined('footer');
        $resolver->setDefined('footer_offset');
        $resolver->setDefined('header');
        $resolver->setDefined('header_offset');

        $resolver->setAllowedTypes('footer', ['bool']);
        $resolver->setAllowedTypes('footer_offset', ['int']);
        $resolver->setAllowedTypes('header', ['bool']);
        $resolver->setAllowedTypes('header_offset', ['int']);

        $resolver->setDefault('footer', false);
        $resolver->setDefault('footer_offset', 0);
        $resolver->setDefault('header', false);
        $resolver->setDefault('header_offset', 0);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return bool
     */
    public function getFooter(): bool
    {
        return $this->footer;
    }

    /**
     * @param bool $footer
     * @return FixedHeader
     */
    public function setFooter(bool $footer): FixedHeader
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * @return int
     */
    public function getFooterOffset(): int
    {
        return $this->footerOffset;
    }

    /**
     * @param int $footerOffset
     * @return FixedHeader
     */
    public function setFooterOffset(int $footerOffset): FixedHeader
    {
        $this->footerOffset = $footerOffset;

        return $this;
    }

    /**
     * @return bool
     */
    public function getHeader(): bool
    {
        return $this->header;
    }

    /**
     * @param bool $header
     * @return FixedHeader
     */
    public function setHeader(bool $header): FixedHeader
    {
        $this->header = $header;

        return $this;
    }

    /**
     * @return int
     */
    public function getHeaderOffset(): int
    {
        return $this->headerOffset;
    }

    /**
     * @param int $headerOffset
     * @return FixedHeader
     */
    public function setHeaderOffset(int $headerOffset): FixedHeader
    {
        $this->headerOffset = $headerOffset;

        return $this;
    }
}
