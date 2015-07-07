<?php

/**
 * This file is part of the WgUniversalDataTableBundle package.
 *
 * (c) stwe <https://github.com/stwe/DataTablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Wg\UniversalDataTable\DataTable\View;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\DependencyInjection\Container;
use Exception;

/**
 * Class Ajax
 *
 * @package Wg\UniversalDataTable\DataTable\View
 */
class Ajax
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

    /**
     * URL set as the Ajax data source for the table.
     *
     * @var string
     */
    protected $url;

    /**
     * Send request as POST or GET.
     *
     * @var string
     */
    protected $type;

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
        $this->configureOptions($this->resolver);
        $this->setOptions($this->options);
    }

    //-------------------------------------------------
    // Setup Ajax
    //-------------------------------------------------

    /**
     * Set options.
     *
     * @param array $options
     *
     * @return $this
     */
    public function setOptions(array $options)
    {
        $this->options = $this->resolver->resolve($options);
        $this->callingSettersWithOptions($this->options);

        return $this;
    }

    /**
     * Configure Options.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    private function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'url' => '',
            'type' => 'GET'
        ));

        $resolver->setAllowedTypes('url', 'string');
        $resolver->setAllowedTypes('type', 'string');

        $resolver->setAllowedValues('type', array('GET', 'POST', 'get', 'post'));

        return $this;
    }

    /**
     * Calling setters with options.
     *
     * @param array $options
     *
     * @return $this
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
                throw new Exception('callingSettersWithOptions(): ' . $method . ' invalid method name');
            }
        }

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set Url.
     *
     * @param string $url
     *
     * @return $this
     */
    protected function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get Url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set Type.
     *
     * @param string $type
     *
     * @return $this
     */
    protected function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get Type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}
