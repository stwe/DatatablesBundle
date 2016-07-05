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

use Sg\DatatablesBundle\OptionsResolver\OptionsInterface;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Exception;

/**
 * Class AbstractViewOptions
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
abstract class AbstractViewOptions implements OptionsInterface
{
    /**
     * Options container.
     *
     * @var array
     */
    protected $options;

    /**
     * @var null|OptionsResolver
     */
    protected $nestedOptionsResolver;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->options = array();
        $this->nestedOptionsResolver = null;
        $this->set($this->options);
    }

    //-------------------------------------------------
    // Set options
    //-------------------------------------------------

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
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);

        if (null !== $this->nestedOptionsResolver) {
            foreach ($options as $key => $value) {
                $this->configureAndResolveNestedOptions($this->options[$key]);
            }
        }

        $this::callingSettersWithOptions($this->options, $this);

        return $this;
    }

    //-------------------------------------------------
    // Static
    //-------------------------------------------------

    /**
     * Calls the setters.
     *
     * @param array            $options
     * @param OptionsInterface $class
     *
     * @throws Exception
     */
    public static function callingSettersWithOptions(array $options, OptionsInterface $class)
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
