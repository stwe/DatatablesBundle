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

use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Factory
 *
 * @package Sg\DatatablesBundle\Datatable
 */
class Factory
{
    /**
     * Create.
     *
     * @param ContainerInterface $container
     * @param mixed $class
     * @param mixed $interface
     *
     * @return mixed
     * @throws Exception
     */
    public static function create(ContainerInterface $container, $class, $interface)
    {
        if (empty($class) || !is_string($class) && !$class instanceof $interface) {
            throw new Exception("Factory::create(): String or $interface expected.");
        }

        if ($class instanceof $interface) {
            return $class;
        }

        if (is_string($class) && class_exists($class)) {
            if ($container->has($class)) {
                $instance = $container->get($class);
            } else {
                $instance = new $class($container);
            }

            if (!$instance instanceof $interface) {
                throw new Exception("Factory::create(): String or $interface expected.");
            } else {
                return $instance;
            }
        } else {
            throw new Exception("Factory::create(): $class is not callable.");
        }
    }
}
