<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Closure;

/**
 * Class Action
 *
 * @package Sg\DatatablesBundle\Datatable\Action
 */
class Action extends AbstractAction
{
    /**
     * Render only if parameter / conditions are TRUE
     *
     * @var Closure|array
     */
    protected $renderIf;

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefault('render_if', array());

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set renderIf.
     *
     * @param Closure|array $renderIf
     *
     * @return $this
     */
    public function setRenderIf($renderIf)
    {
        $this->renderIf = $renderIf;

        return $this;
    }

    /**
     * Get renderIf.
     *
     * @return Closure|array
     */
    public function getRenderIf()
    {
        return $this->renderIf;
    }

    /**
     * Is visible.
     *
     * @param array $data
     *
     * @return boolean
     */
    public function isVisible(array $data = array())
    {
        if (null !== $this->renderIf && !empty($this->renderIf) && !empty($data)) {
            if (is_array($this->renderIf)) {
                $result = false;
                foreach ($this->renderIf as $key => $item) {
                    $result = ($item == $data[$key]);
                }

                return $result;
            } elseif ($this->renderIf instanceof Closure) {
                    return call_user_func($this->renderIf, $data);
            }
        }

        return true;
    }
}
