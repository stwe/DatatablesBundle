<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Action
 *
 * @package Sg\DatatablesBundle\Datatable\Action
 */
class Action extends AbstractAction
{
    /**
     * Render only if parameter / conditions are TRUE
     *
     * @var array
     */
    protected $renderIf;

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('render_if', array());
        $resolver->setAllowedTypes('render_if', 'array');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set renderIf.
     *
     * @param array $renderIf
     *
     * @return $this
     */
    public function setRenderIf(array $renderIf)
    {
        $this->renderIf = $renderIf;

        return $this;
    }

    /**
     * Get renderIf.
     *
     * @return array
     */
    public function getRenderIf()
    {
        return $this->renderIf;
    }
}
