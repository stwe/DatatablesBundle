<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\OptionsResolver;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Interface OptionsInterface
 *
 * @package Sg\DatatablesBundle\OptionsResolver
 */
interface OptionsInterface
{
    /**
     * Configure options.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver);
}
