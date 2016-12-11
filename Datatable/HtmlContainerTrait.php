<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable;

/**
 * Class HtmlContainerTrait
 *
 * @package Sg\DatatablesBundle\Datatable
 */
trait HtmlContainerTrait
{
    /**
     * Start HTML code.
     *
     * @var null|string
     */
    protected $startHtml;

    /**
     * End HTML code.
     *
     * @var null|string
     */
    protected $endHtml;

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get startHtml.
     *
     * @return null|string
     */
    public function getStartHtml()
    {
        return $this->startHtml;
    }

    /**
     * Set startHtml.
     *
     * @param null|string $startHtml
     *
     * @return $this
     */
    public function setStartHtml($startHtml)
    {
        $this->startHtml = $startHtml;

        return $this;
    }

    /**
     * Get endHtml.
     *
     * @return null|string
     */
    public function getEndHtml()
    {
        return $this->endHtml;
    }

    /**
     * Set endHtml.
     *
     * @param null|string $endHtml
     *
     * @return $this
     */
    public function setEndHtml($endHtml)
    {
        $this->endHtml = $endHtml;

        return $this;
    }
}
