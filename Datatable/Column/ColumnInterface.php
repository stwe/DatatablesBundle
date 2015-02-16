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

use Symfony\Component\OptionsResolver\OptionsResolver;
use Exception;

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
     * @return $this
     */
    public function setData($data);

    /**
     * Set dql.
     *
     * @param null|string $data
     *
     * @return $this
     */
    public function setDql($data);

    /**
     * Set options.
     *
     * @param array $options
     *
     * @return $this
     * @throws Exception
     */
    public function setOptions(array $options);

    /**
     * Setup options resolver.
     *
     * @param array $options
     *
     * @return $this
     */
    public function setupOptionsResolver(array $options);

    /**
     * Configure otions.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Get template.
     *
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
