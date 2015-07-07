<?php

/**
 * This file is part of the WgUniversalDataTableBundle package.
 *
 * (c) stwe <https://github.com/stwe/DataTablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wg\UniversalDataTable\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Exception;

/**
 * Interface OptionsInterface
 *
 * @package Wg\UniversalDataTable\DataTable\Column
 */
interface OptionsInterface
{
    /**
     * Setup options resolver.
     *
     * @param array $options
     *
     * @return $this
     */
    public function setupOptionsResolver(array $options);

    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver);

    /**
     * Set options.
     *
     * @param array $options
     *
     * @return $this
     * @throws Exception
     */
    public function setOptions(array $options);
}
