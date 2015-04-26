<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;

/**
 * Class MultiselectColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class MultiselectColumn extends ActionColumn
{
    /**
     * HTML attributes for the checkboxes.
     *
     * @var array
     */
    protected $attributes;


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefault("attributes", array());

        $resolver->addAllowedTypes(array(
            "attributes" => "array",
        ));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return "SgDatatablesBundle:Column:multiselect.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return "multiselect";
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set actions.
     *
     * @param array $actions
     *
     * @return $this
     */
    public function setActions(array $actions)
    {
        foreach ($actions as $action) {
            $newAction = new MultiselectAction();
            $this->actions[] = $newAction->setupOptionsResolver($action);
        }

        return $this;
    }

    /**
     * Set checkbox attributes.
     *
     * @param array $attributes
     *
     * @throws InvalidArgumentException
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        if (array_key_exists("type", $this->attributes)) {
            throw new InvalidArgumentException("The 'type' attribute is not supported.");
        }
        if (array_key_exists("value", $this->attributes)) {
            throw new InvalidArgumentException("The 'value' attribute is not supported.");
        }
        if (array_key_exists("name", $this->attributes)) {
            $this->attributes["name"] = "multiselect_checkbox " . $this->attributes["name"];
        } else {
            $this->attributes["name"] = "multiselect_checkbox";
        }
        if (array_key_exists("class", $this->attributes)) {
            $this->attributes["class"] = "multiselect_checkbox " . $this->attributes["class"];
        } else {
            $this->attributes["class"] = "multiselect_checkbox";
        }

        return $this;
    }

    /**
     * Get checkbox attributes.
     *
     * @return array
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
