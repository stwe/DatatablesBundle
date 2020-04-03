<?php

/*
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Column;

use Sg\DatatablesBundle\Datatable\Editable\EditableInterface;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Helper;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Column extends AbstractColumn
{
    // The Column is editable.
    use EditableTrait;

    use FilterableTrait;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function renderSingleField(array &$row)
    {
        $path = Helper::getDataPropertyPath($this->data);

        if ($this->accessor->isReadable($row, $path)) {
            if ($this->isEditableContentRequired($row)) {
                $content = $this->renderTemplate($this->accessor->getValue($row, $path), $row[$this->editable->getPk()]);
                $this->accessor->setValue($row, $path, $content);
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function renderToMany(array &$row)
    {
        $value = null;
        $path = Helper::getDataPropertyPath($this->data, $value);

        if ($this->accessor->isReadable($row, $path)) {
            if ($this->isEditableContentRequired($row)) {
                // e.g. comments[ ].createdBy.username
                //     => $path = [comments]
                //     => $value = [createdBy][username]

                $entries = $this->accessor->getValue($row, $path);

                if (\count($entries) > 0) {
                    foreach ($entries as $key => $entry) {
                        $currentPath = $path.'['.$key.']'.$value;
                        $currentObjectPath = Helper::getPropertyPathObjectNotation($path, $key, $value);

                        $content = $this->renderTemplate(
                            $this->accessor->getValue($row, $currentPath),
                            $row[$this->editable->getPk()],
                            $currentObjectPath
                        );

                        $this->accessor->setValue($row, $currentPath, $content);
                    }
                }
                // no placeholder - leave this blank
            }
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCellContentTemplate()
    {
        return '@SgDatatables/render/column.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function renderPostCreateDatatableJsContent()
    {
        if ($this->editable instanceof EditableInterface) {
            return $this->twig->render(
                '@SgDatatables/column/column_post_create_dt.js.twig',
                [
                    'column_class_editable_selector' => $this->getColumnClassEditableSelector(),
                    'editable_options' => $this->editable,
                    'entity_class_name' => $this->getEntityClassName(),
                    'column_dql' => $this->dql,
                    'original_type_of_field' => $this->getOriginalTypeOfField(),
                ]
            );
        }

        return null;
    }

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'filter' => [TextFilter::class, []],
            'editable' => null,
        ]);

        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('editable', ['null', 'array']);

        return $this;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Render template.
     *
     * @param string|null $data
     * @param string      $pk
     * @param string|null $path
     *
     * @return mixed|string
     */
    private function renderTemplate($data, $pk, $path = null)
    {
        return $this->twig->render(
            $this->getCellContentTemplate(),
            [
                'data' => $data,
                'column_class_editable_selector' => $this->getColumnClassEditableSelector(),
                'pk' => $pk,
                'path' => $path,
            ]
        );
    }
}
