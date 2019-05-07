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

/**
 * Class Ajax
 *
 * @package Sg\DatatablesBundle\Datatable
 */
class Ajax
{
    /**
     * Use the OptionsResolver.
     */
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - Ajax/Data
    //-------------------------------------------------

    /**
     * URL set as the Ajax data source for the table.
     * Default: null
     *
     * @var null|string
     */
    protected $url;

    /**
     * Send request as POST or GET.
     * Default: 'GET'
     *
     * @var string
     */
    protected $method;

    /**
     * Data to be sent.
     * Default: null
     *
     * @var null|array
     */
    protected $data;

    /**
     * Use Datatables' Pipeline.
     * Default: 0 (disable)
     *
     * @see https://datatables.net/examples/server_side/pipeline.html
     *
     * @var int Number of pages to cache. Set to zero to disable feature.
     */
    protected $pipeline;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ajax constructor.
     */
    public function __construct()
    {
        $this->initOptions();
    }

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * Config options.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'url' => null,
            'method' => 'GET',
            'data' => null,
            'pipeline' => 0,
        ));

        $resolver->setAllowedTypes('url', array('null', 'string'));
        $resolver->setAllowedTypes('method', 'string');
        $resolver->setAllowedTypes('data', array('null', 'array'));
        $resolver->setAllowedTypes('pipeline', 'int');

        $resolver->setAllowedValues('method', array('GET', 'POST'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get url.
     *
     * @return null|string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set url.
     *
     * @param null|string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get method.
     *
     * @return string
     */
    public function getMethod()
    {
        return $this->method;
    }

    /**
     * @return string
     * @deprecated Use getMethod() instead
     */
    public function getType()
    {
        return $this->getMethod();
    }

    /**
     * Set method.
     *
     * @param string $method
     *
     * @return $this
     */
    public function setMethod($method)
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param $method
     *
     * @return \Sg\DatatablesBundle\Datatable\Ajax
     * @deprecated Use setMethod() instead
     */
    public function setType($method)
    {
        return $this->setMethod($method);
    }

    /**
     * Get data.
     *
     * @return null|array
     */
    public function getData()
    {
        if (is_array($this->data)) {
            return $this->optionToJson($this->data);
        }

        return $this->data;
    }

    /**
     * Set data.
     *
     * @param null|array $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get pipeline.
     *
     * @return int
     */
    public function getPipeline()
    {
        return $this->pipeline;
    }

    /**
     * Set pipeline.
     *
     * @param int $pipeline
     *
     * @return $this
     */
    public function setPipeline($pipeline)
    {
        $this->pipeline = $pipeline;

        return $this;
    }
}
