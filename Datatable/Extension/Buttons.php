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
 * Class Buttons
 *
 * @package Sg\DatatablesBundle\Datatable\Extension
 */
class Buttons
{
    /**
     * Use the OptionsResolver.
     */
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - Buttons Extension
    //-------------------------------------------------

    /**
     * List of built-in buttons to show.
     * Default: null
     *
     * @var array|null
     */
    protected $showButtons;

    /**
     * List of buttons to be created.
     * Default: null
     *
     * @var array|null
     */
    protected $createButtons;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Buttons constructor.
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
        $resolver->setDefaults(array(
            'show_buttons' => null,
            'create_buttons' => null,
        ));

        $resolver->setAllowedTypes('show_buttons', array('null', 'array'));
        $resolver->setAllowedTypes('create_buttons', array('null', 'array'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get showButtons.
     *
     * @return array|null
     */
    public function getShowButtons()
    {
        if (is_array($this->showButtons)) {
            return $this->optionToJson($this->showButtons);
        }

        return $this->showButtons;
    }

    /**
     * Set showButtons.
     *
     * @param array|null $showButtons
     *
     * @return $this
     */
    public function setShowButtons($showButtons)
    {
        $this->showButtons = $showButtons;

        return $this;
    }

    /**
     * Get createButtons.
     *
     * @return array|null
     */
    public function getCreateButtons()
    {
        return $this->createButtons;
    }

    /**
     * Set createButtons.
     *
     * @param array|null $createButtons
     *
     * @return $this
     * @throws Exception
     */
    public function setCreateButtons($createButtons)
    {
        if (is_array($createButtons)) {
            if (count($createButtons) > 0) {
                foreach ($createButtons as $button) {
                    $newButton = new Button();
                    $this->createButtons[] = $newButton->set($button);
                }
            } else {
                throw new Exception('Buttons::setCreateButtons(): The createButtons array should contain at least one element.');
            }
        } else {
            $this->createButtons = $createButtons;
        }

        return $this;
    }
}
