<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\View;

/**
 * Interface DatatableThemeInterface
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
interface DatatableThemeInterface
{
    /**
     * Get name of the theme.
     *
     * @return string
     */
    public function getName();

    /**
     * Get name of the twig template.
     *
     * @return string
     */
    public function getTemplate();

    /**
     * Get table classes.
     *
     * @return string
     */
    public function getTableClasses();

    /**
     * Set table classes.
     *
     * @param string $tableClasses
     *
     * @return self
     */
    public function setTableClasses($tableClasses);

    /**
     * Get the form classes.
     *
     * @return string
     */
    public function getFormClasses();

    /**
     * Set form classes.
     *
     * @param string $formClasses
     *
     * @return self
     */
    public function setFormClasses($formClasses);

    /**
     * Get pagination type.
     *
     * @return null|string
     */
    public function getPagination();

    /**
     * Set pagination type.
     *
     * @param null|string $pagination
     *
     * @return self
     */
    public function setPagination($pagination);

    /**
     * Get sDom.
     *
     * @return mixed
     */
    public function getSDom();

    /**
     * Set sDom.
     *
     * @param mixed $sDom
     *
     * @return self
     */
    public function setSDom($sDom);
}