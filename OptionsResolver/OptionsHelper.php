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

use Symfony\Component\DependencyInjection\Container;

/**
 * Class OptionsHelper
 *
 * @package Sg\DatatablesBundle\OptionsResolver
 */
class OptionsHelper
{
    /**
     * Set options.
     *
     * @param array  $options
     * @param object $class
     *
     * @throws \Exception
     */
    public static function setOptions(array $options, $class)
    {
        $methods = get_class_methods($class);

        foreach ($options as $key => $value) {
            $key = Container::camelize($key);
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $class->$method($value);
            } else {
                throw new \Exception('setOptions(): ' . $method . ' invalid method name');
            }
        }
    }
}
