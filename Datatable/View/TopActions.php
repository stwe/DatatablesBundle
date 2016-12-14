<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\View;

use Sg\DatatablesBundle\Datatable\Action\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Closure;

/**
 * Class TopActions
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
class TopActions extends AbstractViewOptions
{
    /**
     * Start HTML.
     *
     * @var string
     */
    protected $startHtml;

    /**
     * End HTML.
     *
     * @var string
     */
    protected $endHtml;

    /**
     * Add Top-Action-Bar only if parameter / conditions are TRUE
     *
     * @var Closure|null
     */
    protected $addIf;

    /**
     * The actions container.
     *
     * @var array
     */
    protected $actions;

    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     */
    public function __construct()
    {
        $this->options = array();
        $this->nestedOptionsResolver = null;
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('actions'));

        $resolver->setDefaults(array(
            'start_html' => '',
            'end_html' => '',
            'add_if' => null
        ));

        $resolver->setAllowedTypes('start_html', 'string');
        $resolver->setAllowedTypes('end_html', 'string');
        $resolver->setAllowedTypes('add_if', array('Closure', 'null'));
        $resolver->setAllowedTypes('actions', 'array');

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set start HTML.
     *
     * @param string $startHtml
     *
     * @return $this
     */
    protected function setStartHtml($startHtml)
    {
        $this->startHtml = $startHtml;

        return $this;
    }

    /**
     * Get start HTML.
     *
     * @return string
     */
    public function getStartHtml()
    {
        return $this->startHtml;
    }

    /**
     * Set end HTML.
     *
     * @param string $endHtml
     *
     * @return $this
     */
    protected function setEndHtml($endHtml)
    {
        $this->endHtml = $endHtml;

        return $this;
    }

    /**
     * Get end HTML.
     *
     * @return string
     */
    public function getEndHtml()
    {
        return $this->endHtml;
    }

    /**
     * Set addIf.
     *
     * @param Closure|null $addIf
     *
     * @return $this
     */
    public function setAddIf($addIf)
    {
        $this->addIf = $addIf;

        return $this;
    }

    /**
     * Get addIf.
     *
     * @return Closure|null
     */
    public function getAddIf()
    {
        return $this->addIf;
    }

    /**
     * Set actions.
     *
     * @param array $actions
     *
     * @return $this
     */
    protected function setActions(array $actions)
    {
        foreach ($actions as $action) {
            $newAction = new Action();
            $this->actions[] = $newAction->setupOptionsResolver($action);
        }

        return $this;
    }

    /**
     * Get actions.
     *
     * @return array
     */
    public function getActions()
    {
        return $this->actions;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Checks whether the Top-Action-Bar may be added.
     *
     * @return boolean
     */
    public function isAddIfClosure()
    {
        if ($this->addIf instanceof Closure) {
            return call_user_func($this->addIf);
        }

        return true;
    }
}
