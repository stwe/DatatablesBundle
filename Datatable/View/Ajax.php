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

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Ajax
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class Ajax extends AbstractViewOptions
{
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
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
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
