<?php

/*
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
use Sg\DatatablesBundle\Datatable\Column\AttributeColumn;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\ImageColumn;
use Sg\DatatablesBundle\Datatable\Column\LinkColumn;
use Sg\DatatablesBundle\Datatable\Column\NumberColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;

class PostDatatable extends AbstractDatatable
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = [])
    {
        $this->ajax->set([
            'url' => '',
            'method' => 'GET',
        ]);

        $this->options->set([
            'individual_filtering' => true,
        ]);

        $this->columnBuilder
            ->add('id', Column::class, [
                'title' => 'Id',
            ])
            ->add('link', LinkColumn::class, [
                'title' => 'Link',
            ])
            ->add('title', Column::class, [
                'title' => 'Title',
            ])
            ->add('boolean', BooleanColumn::class, [
                'title' => 'Boolean',
            ])
            ->add('attribute', AttributeColumn::class, [
                'title' => 'Attribute',
            ])
            ->add('datetime', DateTimeColumn::class, [
                'title' => 'DateTimeColumn',
            ])
            ->add('image', ImageColumn::class, [
                'title' => 'ImageColumn',
                'imagine_filter' => '',
                'relative_path' => '',
            ])
            ->add(null, ActionColumn::class, [
                'title' => 'ActionColumn',
                'actions' => [
                ],
            ])
            ->add('number', NumberColumn::class, [
                'title' => 'NumberColumn',
                'formatter' => new \NumberFormatter('en_US', \NumberFormatter::DECIMAL),
            ])
            ->add('virtual', VirtualColumn::class, [
                'title' => 'VirtualColumn',
            ])
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
