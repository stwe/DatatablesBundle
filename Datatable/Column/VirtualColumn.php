<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author nicodmf
 * @author stwe
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class VirtualColumn
 *
 * @deprecated
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class VirtualColumn extends Column
{
    /**
     * An action label.
     *
     * @var string
     */
    protected $label;

    /**
     * HTML attributes.
     *
     * @var array
     */
    protected $attributes;

    /**
     * Render only if parameter / conditions are TRUE
     *
     * @var array
     */
    protected $renderIf;


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefault("label", "");
        $resolver->setDefault("attributes", array());
        $resolver->setDefault("render_if", array());

        $resolver->addAllowedTypes(array(
            "label" => "string",
            "attributes" => "array",
            "render_if" => "array",
        ));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return "virtual";
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set label.
     *
     * @param string $label
     *
     * @return $this
     */
    public function setLabel($label)
    {
        $this->label = $label;

        return $this;
    }

    /**
     * Get label.
     *
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Set attributes.
     *
     * @param array $attributes
     *
     * @return $this
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * Get attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set render conditions.
     *
     * @param array $renderConditions
     *
     * @return $this
     */
    public function setRenderIf(array $renderConditions)
    {
        $this->renderIf = $renderConditions;

        return $this;
    }

    /**
     * Get render conditions.
     *
     * @return array
     */
    public function getRenderIf()
    {
        return $this->renderIf;
    }
}
