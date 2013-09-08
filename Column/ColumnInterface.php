<?php

namespace Sg\DatatablesBundle\Column;

/**
 * Class ColumnInterface
 *
 * @package Sg\DatatablesBundle\Column
 */
interface ColumnInterface
{
    /**
     * @param boolean $bSearchable
     *
     * @return self
     */
    public function setBSearchable($bSearchable);

    /**
     * @return boolean
     */
    public function getBSearchable();

    /**
     * @param boolean $bSortable
     *
     * @return self
     */
    public function setBSortable($bSortable);

    /**
     * @return boolean
     */
    public function getBSortable();

    /**
     * @param boolean $bVisible
     *
     * @return self
     */
    public function setBVisible($bVisible);

    /**
     * @return boolean
     */
    public function getBVisible();

    /**
     * @param string $mData
     *
     * @return self
     */
    public function setMData($mData);

    /**
     * @return string
     */
    public function getMData();

    /**
     * @param string $mRender
     *
     * @return self
     */
    public function setMRender($mRender);

    /**
     * @return string
     */
    public function getMRender();

    /**
     * @param string $sClass
     *
     * @return self
     */
    public function setSClass($sClass);

    /**
     * @return string
     */
    public function getSClass();

    /**
     * @param string $sDefaultContent
     *
     * @return self
     */
    public function setSDefaultContent($sDefaultContent);

    /**
     * @return string
     */
    public function getSDefaultContent();

    /**
     * @param string $sName
     *
     * @return self
     */
    public function setSName($sName);

    /**
     * @return string
     */
    public function getSName();

    /**
     * @param string $sWidth
     *
     * @return self
     */
    public function setSWidth($sWidth);

    /**
     * @return string
     */
    public function getSWidth();
}