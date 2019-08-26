<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable;

trait HtmlContainerTrait
{
    /**
     * Start HTML code.
     *
     * @var string|null
     */
    protected $startHtml;

    /**
     * End HTML code.
     *
     * @var string|null
     */
    protected $endHtml;

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return string|null
     */
    public function getStartHtml()
    {
        return $this->startHtml;
    }

    /**
     * @param string|null $startHtml
     *
     * @return $this
     */
    public function setStartHtml($startHtml)
    {
        $this->startHtml = $startHtml;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEndHtml()
    {
        return $this->endHtml;
    }

    /**
     * @param string|null $endHtml
     *
     * @return $this
     */
    public function setEndHtml($endHtml)
    {
        $this->endHtml = $endHtml;

        return $this;
    }
}
