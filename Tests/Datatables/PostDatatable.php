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

use Sg\DatatablesBundle\Datatable\View\AbstractDatatableView;
use Sg\DatatablesBundle\Datatable\View\Style;

/**
 * Class PostDatatable
 *
 * @package Sg\DatatablesBundle\Tests\Datatables
 */
class PostDatatable extends AbstractDatatableView
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = array())
    {
        $this->topActions->set(array(
            'actions' => array()
        ));

        $this->features->set(array(
            'scroll_x' => true,
            'extensions' => array(
                'buttons' =>
                    array(
                        'pdf'
                    ),
                'responsive' => true
            )
        ));

        $this->ajax->set(array(
            'url' => '',
            'type' => 'GET'
        ));

        $this->options->set(array(
            'class' => Style::BOOTSTRAP_3_STYLE . ' table-condensed',
            'use_integration_options' => true,
            'force_dom' => false
        ));

        $this->columnBuilder
            ->add('id', 'column', array(
                'title' => 'Id',
            ))
            ->add('title', 'column', array(
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
