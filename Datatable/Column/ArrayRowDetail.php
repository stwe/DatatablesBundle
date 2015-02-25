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
 * Class ArrayColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class ArrayRowDetail extends Column
{
    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------
    /**
     * @var string
     */
    protected $arraydata;
    /**
     * @var string
     */
    protected $arraydatatype;
    /**
     * @var string
     */
    protected $arraydatafield;
    /**
     * Set arraydata
     *
     * @param string $arraydata
     *
     * @return $this
     */
    public function setArraydata($arraydata)
    {
      $this->arraydata=$arraydata;
      return $this;
    }
    /**
     * Get arraydata
     *
     * @return string
     */
    public function getArraydata()
    {
      return $this->arraydata;
    }
     /**
     * Set arraydatatype
     *
     * @param string $arraydata
     *
     * @return $this
     */
    public function setArraydatatype($arraydatatype)
    {
      $this->arraydatatype=$arraydatatype;
      return $this;
    }
    /**
     * Get arraydatatype
     *
     * @return string
     */
    public function getArraydatatype()
    {
      return $this->arraydatatype;
    }
    /**
     * Set arraydatafield
     *
     * @param string $arraydatafield
     *
     * @return $this
     */
    public function setArraydatafield($arraydatafield)
    {
      $this->arraydatafield=$arraydatafield;
      return $this;
    }
    /**
     * Get arraydatafield
     *
     * @return string
     */
    public function getArraydatafield()
    {
      return $this->arraydatafield;
    }
   
    /**
     * {@inheritdoc}
     */
    public function setData($data)
    {
        if (empty($data) || !is_string($data)) {
            throw new InvalidArgumentException("setData(): Expecting non-empty string.");
        }

        if (false === strstr($data, '.')) {
            throw new InvalidArgumentException("setData(): An association is expected.");
        }

        $this->data = $data;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver->setDefaults(array(
           
            "searchable" => false,

            "visible" => false,
            "visibleonrow" => true,
            "arraydata" => "",
            "arraydatatype" => "",
            "arraydatafield" => ""
        ));
        $resolver->setRequired(array("data"));
        $resolver->addAllowedTypes(array("data"=>"string",
                            "arraydata"=>"string",
                            "arraydatatype"=>"string",
                            "arraydatafield"=>"string",
                            "render"=>array("string")));
        

        return $this;
    }
     /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return "SgDatatablesBundle:Column:arrayrow.html.twig";
    }
    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return "arrayrowdetail";
    }
}
