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
        $resolver->setRequired('details');

        $resolver->setAllowedTypes('details', array('array', 'bool'));

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
            foreach ($details as $key => $value) {
                if (false === in_array($key, array('type', 'target', 'renderer', 'display'))) {
                    throw new Exception(
                        "Responsive::setDetails(): $key is not an valid option."
                    );
                }
            }

            if (is_array($details['renderer'])) {
                $this->validateArrayForTemplateAndOther($details['renderer']);
            }

            if (is_array($details['display'])) {
                $this->validateArrayForTemplateAndOther($details['display']);
            }
        }

        $this->details = $details;

        return $this;
    }
}
