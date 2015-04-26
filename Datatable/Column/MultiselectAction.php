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
        $resolver->setRequired(array("route"));

        $resolver->setDefaults(array(
            "label" => "",
            "role" => "",
        ));

        $resolver->setAllowedTypes(array(
            "route" => "string",
            "label" => "string",
            "role" => "string",
        ));

        return $this;
    }
}
