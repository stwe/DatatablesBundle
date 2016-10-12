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
    use OptionsTrait;

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

    /**
     * Use Datatables' Pipeline.
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
            'url' => '',
            'type' => 'GET',
            'pipeline' => 0
        ));

        $resolver->setAllowedTypes('url', 'string');
        $resolver->setAllowedTypes('type', 'string');
        $resolver->setAllowedTypes('pipeline', 'int');

        $resolver->setAllowedValues('type', array('GET', 'POST'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get url.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return $this
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get type.
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set type.
     *
     * @param string $type
     *
     * @return $this
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get pipeline
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
