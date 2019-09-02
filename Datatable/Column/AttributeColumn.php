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

use Closure;
use Exception;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Helper;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeColumn extends AbstractColumn
{
    // The AttributeColumn is filterable.
    use FilterableTrait;

    /**
     * The Attributes container.
     * A required option.
     *
     * @var Closure
     */
    protected $attributes;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function renderSingleField(array &$row, array &$resultRow)
    {
        $renderAttributes = \call_user_func($this->attributes, $row);

        $path = Helper::getDataPropertyPath($this->data);

        $content = $this->twig->render(
            $this->getCellContentTemplate(),
            [
                'attributes' => $renderAttributes,
                'data' => $this->accessor->getValue($row, $path),
            ]
        );

        $this->accessor->setValue($resultRow, $path, $content);
    }

    /**
     * {@inheritdoc}
     */
    public function renderToMany(array &$row, array &$resultRow)
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

                        $this->accessor->setValue($resultRow, $currentPath, $content);
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
        return '@SgDatatables/render/attributeColumn.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function renderPostCreateDatatableJsContent()
    {
        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function getColumnType()
    {
        return parent::ACTION_COLUMN;
    }

    //-------------------------------------------------
    // Options
    //-------------------------------------------------

    /**
     * Config options.
     *
     * @return $this
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'filter' => [TextFilter::class, []],
            'attributes' => null,
        ]);

        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('attributes', ['null', 'array', 'Closure']);

        return $this;
    }

    /**
     * Get attributes.
     *
     * @return Attributes[]
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

    /**
     * Set attributes.
     *
     * @param Closure $attributes
     *
     * @throws Exception
     *
     * @return $this
     */
    public function setAttributes($attributes)
    {
        $this->attributes = $attributes;

        return $this;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Render template.
     *
     * @param string|null $data
     *
     * @return mixed|string
     */
    private function renderTemplate($data)
    {
        return $this->twig->render(
            $this->getCellContentTemplate(),
            [
                'data' => $data,
            ]
        );
    }
}
