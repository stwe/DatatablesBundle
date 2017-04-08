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

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Exception;

/**
 * Class Responsive
 *
 * @package Sg\DatatablesBundle\Datatable\Extension
 */
class Responsive
{
    /**
     * Use the OptionsResolver.
     */
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - Responsive Extension
    //-------------------------------------------------

    /**
     * Responsive details options.
     * Required option.
     *
     * @var array|bool
     */
    protected $details;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Responsive constructor.
     */
    public function __construct()
    {
        $this->initOptions(false);
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
        $resolver->setRequired('details');

        $resolver->setAllowedTypes('details', array('array', 'bool'));

        /** @noinspection PhpUnusedParameterInspection */
        $resolver->setNormalizer('details', function (Options $options, $value) {
            if (is_array($value)) {
                $this->nestedOptionsResolver = new OptionsResolver();
            }

            return $value;
        });

        return $this;
    }

    /**
     * Configure and resolve nested options.
     *
     * @param array $options
     *
     * @return $this
     */
    public function configureAndResolveNestedOptions(array $options)
    {
        $this->nestedOptionsResolver->setDefaults(array(
            'type' => null,
            'target' => null,
            'renderer' => null,
            'display' => null,
        ));

        $this->nestedOptionsResolver->setAllowedTypes('type', array('string', 'null'));
        $this->nestedOptionsResolver->setAllowedTypes('target', array('int', 'string', 'null'));
        $this->nestedOptionsResolver->setAllowedTypes('renderer', array('array', 'null'));
        $this->nestedOptionsResolver->setAllowedTypes('display', array('array', 'null'));

        $this->nestedOptionsResolver->setAllowedValues('type', array(null, 'inline', 'column'));

        $this->nestedOptionsResolver->resolve($options);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get details.
     *
     * @return array|bool
     */
    public function getDetails()
    {
        return $this->details;
    }

    /**
     * Set details.
     *
     * @param array|bool $details
     *
     * @return $this
     * @throws Exception
     */
    public function setDetails($details)
    {
        if (is_array($details)) {
            $nestedOptions = array('renderer', 'display');
            $allowedNewNestedOptions = array('template', 'vars');

            foreach ($details as $detailKey => $detailValue) {
                if (true === in_array($detailKey, $nestedOptions)) {
                    if (false === array_key_exists('template', $detailValue)) {
                        throw new Exception(
                            'Responsive::setDetails(): The "template" option is required.'
                        );
                    }

                    foreach ($detailValue as $key => $value) {
                        if (false === in_array($key, $allowedNewNestedOptions)) {
                            throw new Exception(
                                "Responsive::setDetails(): $key is not an valid option."
                            );
                        }
                    }
                }
            }
        }

        $this->details = $details;

        return $this;
    }
}
