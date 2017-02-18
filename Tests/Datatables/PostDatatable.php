<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Tests\Datatables;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Column\Column;

/**
 * Class PostDatatable
 *
 * @package Sg\DatatablesBundle\Tests\Datatables
 */
class PostDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->ajax->set(array(
            'url' => '',
            'type' => 'GET',
        ));

        $this->options->set(array(
            'individual_filtering' => true,
        ));

        $this->columnBuilder
            ->add('id', Column::class, array(
                'title' => 'Id',
            ))
            ->add('title', Column::class, array(
                'title' => 'Title',
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return 'AppBundle\Entity\Post';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'post_datatable';
    }
}
