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

use Sg\DatatablesBundle\Datatable\Helper;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Editable\EditableInterface;

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
        if (false === $this->isAssociation()) {
            if ($this->editable instanceof EditableInterface && true === $this->editable->callEditableIfClosure($row)) {
                $this->renderEditableContent($row, $this->data);
            }
        } else {
            if ($this->editable instanceof EditableInterface && true === $this->editable->callEditableIfClosure($row)) {
                $toMany = strpos($this->data, ',');

                if (false === $toMany) {
                    $this->renderEditableContent($row, $this->data);
                } else {
                    // @todo: editable content for toMany associations
                }
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function renderPostCreateDatatableJsContent()
    {
        if ($this->editable instanceof EditableInterface) {
            return $this->twig->render(
                'SgDatatablesBundle:column:column_post_create_dt.js.twig',
                array(
                    'column_class_editable_selector' => $this->getColumnClassEditableSelector(),
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
     * Render editable content.
     *
     * @param array  $row
     * @param string $data
     *
     * @return $this
     */
    private function renderEditableContent(array &$row, $data)
    {
        $path = Helper::getDataPropertyPath($data);

        $render = array(
            'data' => $this->accessor->getValue($row, $path),
            'column_class_editable_selector' => $this->getColumnClassEditableSelector(),
            'pk' => $row[$this->editable->getPk()]
        );

        $content = $this->twig->render(
            'SgDatatablesBundle:render:column.html.twig',
            $render
        );

        $this->accessor->setValue($row, $path, $content);

        return $this;
    }
}
