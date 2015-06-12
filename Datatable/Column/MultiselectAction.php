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

/**
 * Class MultiselectAction
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class MultiselectAction extends Action
{
    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array('route'));

        $resolver->setDefaults(array(
            'icon' => '',
            'label' => '',
            'role' => '',
            'route_parameters' => array(),
            'attributes' => array(),
        ));

        $resolver->setAllowedTypes('icon', 'string');
        $resolver->setAllowedTypes('route', 'string');
        $resolver->setAllowedTypes('label', 'string');
        $resolver->setAllowedTypes('role', 'string');
        $resolver->setAllowedTypes('route_parameters', 'array');
        $resolver->setAllowedTypes('attributes', 'array');

        $resolver->setNormalizer('attributes', function($options, $value) {
            $value['class'] = array_key_exists('class', $value) ? ($value['class'] . ' multiselect_action_click') : 'multiselect_action_click';

            return $value;
        });

        return $this;
    }
}
