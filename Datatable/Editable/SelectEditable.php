<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Editable;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Exception;

/**
 * Class SelectEditable
 *
 * @package Sg\DatatablesBundle\Datatable\Editable
 */
class SelectEditable extends AbstractEditable
{
    /**
     * Source data for list.
     * Default: array()
     *
     * @var array
     */
    protected $source;

    //-------------------------------------------------
    // FilterInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'select';
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
        parent::configureOptions($resolver);

        $resolver->setRequired('source');

        $resolver->setAllowedTypes('source', 'array');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get source.
     *
     * @return array
     */
    public function getSource()
    {
        return $this->optionToJson($this->source);
    }

    /**
     * Set source.
     *
     * @param array $source
     *
     * @return $this
     * @throws Exception
     */
    public function setSource(array $source)
    {
        if (empty($source)) {
            throw new Exception('SelectEditable::setSource(): The source array should contain at least one element.');
        }

        $this->source = $source;

        return $this;
    }
}
