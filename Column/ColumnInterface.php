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

/**
 * Class ColumnInterface
 *
 * @package Sg\DatatablesBundle\Column
 */
interface ColumnInterface
{
    /**
     * Set name.
     *
     * @param null|string $name
     *
     * @return self
     */
    public function setName($name);

    /**
     * Get name.
     *
     * @return null|string
     */
    public function getName();

    /**
     * Set mData.
     *
     * @param string $mData
     *
     * @return self
     */
    public function setMData($mData);

    /**
     * Get mData.
     *
     * @return string
     */
    public function getMData();

    /**
     * Set bSearchable.
     *
     * @param boolean $bSearchable
     *
     * @return self
     */
    public function setBSearchable($bSearchable);

    /**
     * Get bSearchable.
     *
     * @return boolean
     */
    public function getBSearchable();

    /**
     * Set bSortable.
     *
     * @param boolean $bSortable
     *
     * @return self
     */
    public function setBSortable($bSortable);

    /**
     * Get bSortable.
     *
     * @return boolean
     */
    public function getBSortable();

    /**
     * Set bVisible.
     *
     * @param boolean $bVisible
     *
     * @return self
     */
    public function setBVisible($bVisible);

    /**
     * Get bVisible.
     *
     * @return boolean
     */
    public function getBVisible();

    /**
     * Set sTitle.
     *
     * @param null|string $sTitle
     *
     * @return self
     */
    public function setSTitle($sTitle);

    /**
     * Get sTitle.
     *
     * @return null|string
     */
    public function getSTitle();

    /**
     * Set mRender.
     *
     * @param null|string $mRender
     *
     * @return self
     */
    public function setMRender($mRender);

    /**
     * Get mRender.
     *
     * @return null|string
     */
    public function getMRender();

    /**
     * Set sClass.
     *
     * @param string $sClass
     *
     * @return self
     */
    public function setSClass($sClass);

    /**
     * Get sClass.
     *
     * @return string
     */
    public function getSClass();

    /**
     * Set sDefaultContent.
     *
     * @param null|string $sDefaultContent
     *
     * @return self
     */
    public function setSDefaultContent($sDefaultContent);

    /**
     * Get sDefaultContent.
     *
     * @return null|string
     */
    public function getSDefaultContent();

    /**
     * Set sWidth.
     *
     * @param null|string $sWidth
     *
     * @return self
     */
    public function setSWidth($sWidth);

    /**
     * Get sWidth.
     *
     * @return null|string
     */
    public function getSWidth();

    /**
     * Set options.
     *
     * @param array $options
     *
     * @return self
     */
    public function setOptions(array $options);

    /**
     * Returns the id of the column class.
     *
     * @return string
     */
    public function getClassId();
}