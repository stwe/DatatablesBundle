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

use Exception;

/**
 * Class MultiselectAction
 *
 * @package Sg\DatatablesBundle\Datatable\Action
 */
class MultiselectAction extends Action
{
    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set attributes.
     *
     * @param null|array $attributes
     *
     * @return $this
     * @throws Exception
     */
    public function setAttributes($attributes)
    {
        $value = 'sg-datatables-'.$this->datatableName.'-multiselect-action';

        if (is_array($attributes)) {
            if (array_key_exists('href', $attributes)) {
                throw new Exception('MultiselectAction::setAttributes(): The href attribute is not allowed in this context.');
            }

            if (array_key_exists('class', $attributes)) {
                $attributes['class'] = $value.' '.$attributes['class'];
            } else {
                $attributes['class'] = $value;
            }
        } else {
            $attributes['class'] = $value;
        }

        $this->attributes = $attributes;

        return $this;
    }
}
