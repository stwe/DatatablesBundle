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

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
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
     * The PropertyAccessor.
     *
     * @var PropertyAccessor
     */
    protected $accessor;

    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * Init optionsTrait.
     *
     * @param bool $callSetOptions
     *
     * @return $this
     */
    public function initOptions($callSetOptions = true)
    {
        $this->options = array();
        $this->accessor = PropertyAccess::createPropertyAccessorBuilder()
            ->enableMagicCall()
            ->getPropertyAccessor();

        if (true === $callSetOptions) {
            $this->set($this->options);
        }

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
        /** @noinspection PhpUndefinedMethodInspection */
        $this->configureOptions($resolver);

        $this->options = $resolver->resolve($options);
        $this->callingSettersWithOptions($this->options);

        return $this;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Calls the setters.
     *
     * @param array $options
     *
     * @return $this
     */
    private function callingSettersWithOptions(array $options)
    {
        foreach ($options as $setter => $value) {
            $this->accessor->setValue($this, $setter, $value);
        }

        return $this;
    }

    /**
     * Option to JSON.
     *
     * @param mixed $value
     *
     * @return mixed
     */
    protected function optionToJson($value)
    {
        if (is_array($value) && !empty($value)) {
            return json_encode($value);
        }

        return $value;
    }
}
