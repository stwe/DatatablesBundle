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

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Assign array by path.
     *
     * @link http://stackoverflow.com/questions/9635968/convert-dot-syntax-like-this-that-other-to-multi-dimensional-array-in-php
     *
     * @param        $arr
     * @param        $path
     * @param        $value
     * @param string $separator
     */
    private function assignArrayByPath(&$arr, $path, $value, $separator = '.')
    {
        $keys = explode($separator, $path);

        foreach ($keys as $key) {
            $arr = &$arr[$key];
        }

        $arr = $value;
    }

    /**
     * array_intersect_assoc() recursively
     *
     * @param $arr1
     * @param $arr2
     *
     * @see http://stackoverflow.com/questions/4627076/php-question-how-to-array-intersect-assoc-recursively
     *
     * @return array|bool
     */
    private function arrayIntersectAssocRecursive($arr1, $arr2)
    {
        if (!is_array($arr1) || !is_array($arr2)) {
            return (string)$arr1 == (string)$arr2;
        }

        $commonkeys = array_intersect(array_keys($arr1), array_keys($arr2));
        $ret = array();

        foreach ($commonkeys as $key) {
            $res = $this->arrayIntersectAssocRecursive($arr1[$key], $arr2[$key]);

            if ($res) {
                $ret[$key] = $arr1[$key];
            }
        }

        return $ret;
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
                    if (strpos($key, '.') !== false) {
                        $array = array();
                        $this->assignArrayByPath($array, $key, $item);
                        if (empty($this->arrayIntersectAssocRecursive($array, $data))) {
                            $result = false;
                            break;
                        } else {
                            $result = true;
                        }
                    } else {
                        $result = ($item == $data[$key]);
                    }
                }

                return $result;
            } elseif ($this->renderIf instanceof Closure) {
                    return call_user_func($this->renderIf, $data);
            }
        }

        return true;
    }
}
