<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Extension;

use Exception;
use Sg\DatatablesBundle\Datatable\OptionsTrait;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Buttons
{
    use OptionsTrait;

    //-------------------------------------------------
    // DataTables - Buttons Extension
    //-------------------------------------------------

    /**
     * List of built-in buttons to show.
     * Default: null.
     *
     * @var array|null
     */
    protected $showButtons;

    /**
     * List of buttons to be created.
     * Default: null.
     *
     * @var array|null
     */
    protected $createButtons;

    public function __construct()
    {
        $this->initOptions();
    }

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'show_buttons' => null,
            'create_buttons' => null,
        ]);

        $resolver->setAllowedTypes('show_buttons', ['null', 'array']);
        $resolver->setAllowedTypes('create_buttons', ['null', 'array']);

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return array|null
     */
    public function getShowButtons()
    {
        if (\is_array($this->showButtons)) {
            return $this->optionToJson($this->showButtons);
        }

        return $this->showButtons;
    }

    /**
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
     * @return array|null
     */
    public function getCreateButtons()
    {
        return $this->createButtons;
    }

    /**
     * @param array|null $createButtons
     *
     * @throws Exception
     *
     * @return $this
     */
    public function setCreateButtons($createButtons)
    {
        if (\is_array($createButtons)) {
            if (\count($createButtons) > 0) {
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
