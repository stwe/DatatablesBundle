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

use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Editable\Editable;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class Column
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class Column extends AbstractColumn
{
    /**
     * The Column is editable.
     */
    use EditableTrait;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getTemplate()
    {
        return 'SgDatatablesBundle:column:column.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function renderCellContent(array &$row)
    {
        if ($this->editable instanceof Editable && true === $this->editable->callEditableIfClosure($row)) {
            $row[$this->data] = $this->twig->render(
                'SgDatatablesBundle:render:column.html.twig',
                array(
                    'column_class_selector' => $this->getColumnClassEditableSelector(),
                    'data' => $row[$this->data],
                    'pk' => $row[$this->editable->getPk()]
                )
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function renderPostCreateDatatableJsContent()
    {
        if ($this->editable instanceof  Editable) {
            return $this->twig->render(
                'SgDatatablesBundle:column:column_post_create_dt.js.twig',
                array(
                    'column_class_selector' => $this->getColumnClassEditableSelector(),
                    'editable_options' => $this->editable,
                    'entity_class_name' => $this->getEntityClassName(),
                    'column_dql' => $this->dql
                )
            );
        }

        return null;
    }

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * Config options.
     *
     * @param OptionsResolver $resolver
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults(array(
            'filter' => array(TextFilter::class, array()),
            'editable' => null
        ));

        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('editable', array('null', 'array'));

        return $this;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Get class selector name.
     *
     * @return string
     */
    private function getColumnClassEditableSelector()
    {
        return 'sg-datatables-' . $this->getDatatableName() . '-editable-column-' . $this->index;
    }
}
