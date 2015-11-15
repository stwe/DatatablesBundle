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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Exception;

/**
 * Class BaseOptions
 *
 * @package Sg\DatatablesBundle\OptionsResolver
 */
class BaseOptions
{
    /**
     * Options container.
     *
     * @var array
     */
    protected $options;

    /**
     * An OptionsResolver instance.
     *
     * @var OptionsResolver
     */
    protected $resolver;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->options = array();
        $this->resolver = new OptionsResolver();
    }

    /**
     * Set options.
     *
     * @param array $options
     *
     * @return $this
     * @throws Exception
     */
    public function set(array $options)
    {
        $this->options = $this->resolver->resolve($options);
        $this->callingSettersWithOptions($this->options, $this);

        return $this;
    }

    /**
     * Calls the setters.
     *
     * @param array  $options
     * @param object $class
     *
     * @throws Exception
     */
    public static function callingSettersWithOptions(array $options, $class)
    {
        $methods = get_class_methods($class);

        foreach ($options as $key => $value) {
            $key = Container::camelize($key);
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $class->$method($value);
            } else {
                throw new Exception('callingSettersWithOptions(): ' . $method . ' invalid method name');
            }
        }
    }
}
