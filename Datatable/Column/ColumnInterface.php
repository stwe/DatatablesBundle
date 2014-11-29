<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

/**
 * Interface ColumnInterface
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
interface ColumnInterface
{
    /**
     * Set data.
     *
     * @param null|string $data
     *
     * @return self
     */
    public function setData($data);

    /**
     * Set dql.
     *
     * @param null|string $data
     *
     * @return self
     */
    public function setDql($data);

    /**
     * Set defaults.
     *
     * @return self
     */
    public function setDefaults();

    /**
     * Set options.
     *
     * @param array $options
     *
     * @return self
     */
    public function setOptions(array $options);

    /**
     * @return string
     */
    public function getTemplate();

    /**
     * Get alias.
     *
     * @return string
     */
    public function getAlias();
}
