<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Extension;

use Sg\DatatablesBundle\Datatable\OptionsTrait;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Exception;

/**
 * Class RowGroup
 *
 * @package Sg\DatatablesBundle\Datatable\Extension
 */
class RowGroup
{
    /**
     * Use the OptionsResolver.
     */
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - RowGroup Extension
    //-------------------------------------------------

    /**
     * The data point to get the grouping information.
     * Required option.
     *
     * @var string
     */
    protected $dataSrc;

    /**
     * Provide a function that can be used to control the data shown in the start grouping row.
     * Optional.
     *
     * @var array
     */
    protected $startRender;

    /**
     * Provide a function that can be used to control the data shown in the end grouping row.
     * Optional.
     *
     * @var array
     */
    protected $endRender;

    /**
     * Set the class name to be used for the grouping rows.
     * Optional.
     *
     * @var string
     */
    protected $className;

    /**
     * Text to show for rows which have null or undefined group data.
     * Optional.
     *
     * @var string
     */
    protected $emptyDataGroup;

    /**
     * Provides the ability to disable row grouping at initialisation
     * Optional.
     *
     * @var bool
     */
    protected $enable;

    /**
     * Set the class name to be used for the grouping end rows
     * Optional.
     *
     * @var string
     */
    protected $endClassName;

    /**
     * Set the class name to be used for the grouping start rows
     * Optional.
     *
     * @var string
     */
    protected $startClassName;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * RowGroup constructor.
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
        $resolver->setRequired('data_src');
        $resolver->setDefined('start_render');
        $resolver->setDefined('end_render');
        $resolver->setDefined('enable');
        $resolver->setDefined('class_name');
        $resolver->setDefined('empty_data_group');
        $resolver->setDefined('end_class_name');
        $resolver->setDefined('start_class_name');

        $resolver->setDefaults(array(
            'enable' => true,
        ));

        $resolver->setAllowedTypes('data_src', array('string'));
        $resolver->setAllowedTypes('start_render', array('array'));
        $resolver->setAllowedTypes('end_render', array('array'));
        $resolver->setAllowedTypes('enable', array('bool'));
        $resolver->setAllowedTypes('class_name', array('string'));
        $resolver->setAllowedTypes('empty_data_group', array('string'));
        $resolver->setAllowedTypes('end_class_name', array('string'));
        $resolver->setAllowedTypes('start_class_name', array('string'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get details.
     *
     * @return string
     */
    public function getDataSrc()
    {
        return $this->dataSrc;
    }

    /**
     * Set details.
     *
     * @param string $dataSrc
     *
     * @return $this
     * @throws Exception
     */
    public function setDataSrc($dataSrc)
    {
        if (is_string($dataSrc) && empty($dataSrc)) {
            throw new \Exception(
                "RowGroup::setDataSrc(): the column name is empty."
            );
        }

        $this->dataSrc = $dataSrc;

        return $this;
    }

    /**
     * @return string
     */
    public function getStartRender()
    {
        return $this->startRender;
    }

    /**
     * @param string $startRender
     * @return RowGroup
     */
    public function setStartRender($startRender)
    {
        if (false === array_key_exists('template', $startRender)) {
            throw new Exception(
                'RowGroup::setStartRender(): The "template" option is required.'
            );
        }

        foreach ($startRender as $key => $value) {
            if (false === in_array($key, array('template', 'vars',))) {
                throw new Exception(
                    "RowGroup::setStartRender(): $key is not a valid option."
                );
            }
        }

        $this->startRender = $startRender;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndRender()
    {
        return $this->endRender;
    }

    /**
     * @param string $endRender
     * @return RowGroup
     */
    public function setEndRender($endRender)
    {
        if (false === array_key_exists('template', $startRender)) {
            throw new Exception(
                'RowGroup::setEndRender(): The "template" option is required.'
            );
        }

        foreach ($startRender as $key => $value) {
            if (false === in_array($key, array('template', 'vars',))) {
                throw new Exception(
                    "RowGroup::setEndRender(): $key is not a valid option."
                );
            }
        }

        $this->endRender = $endRender;

        return $this;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->className;
    }

    /**
     * @param string $className
     * @return RowGroup
     */
    public function setClassName($className)
    {
        if (is_string($className) && empty($className)) {
            throw new \Exception(
                "RowGroup::setClassName(): the class name is empty."
            );
        }

        $this->className = $className;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmptyDataGroup()
    {
        return $this->emptyDataGroup;
    }

    /**
     * @param string $emptyDataGroup
     * @return RowGroup
     */
    public function setEmptyDataGroup($emptyDataGroup)
    {
        if (is_string($emptyDataGroup) && empty($emptyDataGroup)) {
            throw new \Exception(
                "RowGroup::setEmptyDataGroup(): the empty data group text is empty."
            );
        }

        $this->emptyDataGroup = $emptyDataGroup;

        return $this;
    }

    /**
     * @return bool
     */
    public function getEnable()
    {
        return $this->enable;
    }

    /**
     * @param bool $enable
     * @return RowGroup
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;

        return $this;
    }

    /**
     * @return string
     */
    public function getEndClassName()
    {
        return $this->endClassName;
    }

    /**
     * @param string $endClassName
     * @return RowGroup
     */
    public function setEndClassName($endClassName)
    {
        if (is_string($endClassName) && empty($endClassName)) {
            throw new \Exception(
                "RowGroup::setEndClassName(): the end class name is empty."
            );
        }

        $this->endClassName = $endClassName;

        return $this;
    }

    /**
     * @return string
     */
    public function getStartClassName()
    {
        return $this->startClassName;
    }

    /**
     * @param string $startClassName
     * @return RowGroup
     */
    public function setStartClassName($startClassName)
    {
        if (is_string($startClassName) && empty($startClassName)) {
            throw new \Exception(
                "RowGroup::setStartClassName(): the start class name is empty."
            );
        }

        $this->startClassName = $startClassName;

        return $this;
    }
}
