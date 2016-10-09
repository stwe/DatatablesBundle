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

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Exception;

/**
 * Class OptionsTrait
 *
 * @package Sg\DatatablesBundle\Datatable
 */
trait OptionsTrait
{
    /**
     * Options container.
     *
     * @var array
     */
    protected $options;

    /**
     * Init optionsTrait.
     *
     * @return $this
     */
    private function initOptions()
    {
        $this->options = array();
        $this->set($this->options);

        return $this;
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
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
        $this->callingSettersWithOptions($this->options);

        return $this;
    }

    /**
     * Calls the setters.
     *
     * @param array $options
     *
     * @throws Exception
     */
    private function callingSettersWithOptions(array $options)
    {
        $methods = get_class_methods($this);

        foreach ($options as $key => $value) {
            $key = Container::camelize($key);
            $method = 'set' . ucfirst($key);
            if (in_array($method, $methods)) {
                $this->$method($value);
            } else {
                throw new Exception("OptionsTrait::callingSettersWithOptions(): $method invalid method name.");
            }
        }
    }

    /**
     * Get option as json pretty print.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    private function getOptionAsJsonPrettyPrint($value)
    {
        if (is_array($value) && !empty($value)) {
            return json_encode($value, JSON_PRETTY_PRINT);
        }

        return $value;
    }

    /**
     * Check options.
     *
     * @param array $array
     * @param array $options
     *
     * @return bool
     * @throws \Exception
     */
    private function checkOptions(array $array, array $options)
    {
        foreach($array as $key => $value) {
            if (!in_array($key, $options, true)) {
                throw new Exception("OptionsTrait::checkOptions(): $key is not a valid option.");
            }
        }

        return true;
    }
}
