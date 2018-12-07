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
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\PropertyAccess\PropertyAccessor;
use function array_key_exists;
use function in_array;
use function is_array;
use function json_encode;

/**
 * Class OptionsTrait
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
     * @param bool $resolve
     *
     * @return $this
     */
    public function initOptions($resolve = false)
    {
        $this->options = [];

        /** @noinspection PhpUndefinedMethodInspection */
        $this->accessor = PropertyAccess::createPropertyAccessorBuilder()
            ->enableMagicCall()
            ->getPropertyAccessor();

        if ($resolve === true) {
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
     *
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
        if (is_array($value)) {
            return json_encode($value);
        }

        return $value;
    }

    /**
     * Validates an array whether the "template" and "vars" options are set.
     *
     * @param array $array
     * @param array $other
     *
     * @return bool
     *
     * @throws Exception
     */
    protected function validateArrayForTemplateAndOther(array $array, array $other = ['template', 'vars'])
    {
        if (array_key_exists('template', $array) === false) {
            throw new Exception(
                'OptionsTrait::validateArrayForTemplateAndOther(): The "template" option is required.'
            );
        }

        foreach ($array as $key => $value) {
            if (in_array($key, $other) === false) {
                throw new Exception(
                    "OptionsTrait::validateArrayForTemplateAndOther(): $key is not an valid option."
                );
            }
        }

        return true;
    }
}
