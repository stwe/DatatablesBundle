<?php

/**
 * This file is part of the WgUniversalDataTableBundle package.
 *
 * (c) stwe <https://github.com/stwe/DataTablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @author nicodmf
 * @author stwe
 */

namespace Wg\UniversalDataTable\DataTable\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class VirtualColumn
 *
 * @package Wg\UniversalDataTable\DataTable\Column
 */
class VirtualColumn extends Column
{
    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getAlias()
    {
        return 'virtual';
    }

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);
        
        $resolver->setDefaults(array(
            'orderable' => false,
            'searchable' => false,
        ));

        return $this;
    }
}
