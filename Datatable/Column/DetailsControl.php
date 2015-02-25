<?php

namespace Sg\DatatablesBundle\Datatable\Column;

use Sg\DatatablesBundle\Datatable\Column\AbstractColumn;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\Exception\InvalidArgumentException;
/**
 * Description of customColumn
 *
 * @author Rene Arias <renearias@multiservices.com.ec>
 */
class DetailsControl extends AbstractColumn{
   
    /**
     * Default content.
     *
     * @var string
     */
    protected $default;


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
   public function setData($data)
    {
        if (null !== $data) {
            throw new InvalidArgumentException("setData(): Null expected.");
        }

        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            "class" => "details-control",
            "classicon" => "",
            "padding" => "",
            "name" => "",
            
            "render" => null,
            
            "title" => "",
            "type" => "",
            "visible" => true,
            "width" => "",
            "default" => ""
        ));

        $resolver->setAllowedTypes(array(
            "class" => "string",
            "padding" => "string",
            "name" => "string",
            
            "render" => array("string", "null"),
            
            "title" => "string",
            "type" => "string",
            "visible" => "bool",
            "width" => "string",
            "default" => "string"
        ));

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return "SgDatatablesBundle:Column:detailscontrol.html.twig";
    }

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return "details_control";
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get default.
     *
     * @return string
     */
    public function getDefault()
    {
        return $this->default;
    }

    /**
     * Set default.
     *
     * @param string $default
     *
     * @return $this
     */
    public function setDefault($default)
    {
        $this->default = $default;

        return $this;
    }
}
