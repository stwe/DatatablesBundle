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
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\ImageColumn;
use Sg\DatatablesBundle\Datatable\Column\NumberColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

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
            ->add('boolean', BooleanColumn::class, array(
                'title' => 'Boolean',
            ))
            ->add('datetime', DateTimeColumn::class, array(
                'title' => 'DateTimeColumn',
            ))
            ->add('image', ImageColumn::class, array(
                'title' => 'ImageColumn',
                'imagine_filter' => '',
                'relative_path' => '',
            ))
            ->add(null, ActionColumn::class, array(
                'title' => 'ActionColumn',
                'actions' => [
                ]
            ))
            ->add('number', NumberColumn::class, array(
                'title' => 'NumberColumn',
                'formatter' => new \NumberFormatter('en_US', \NumberFormatter::DECIMAL)
            ))
            ->add('virtual', VirtualColumn::class, array(
                'title' => 'VirtualColumn',
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
