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
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Helper;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BooleanColumn extends AbstractColumn
{
    use EditableTrait;

    use FilterableTrait;

    /**
     * @internal
     */
    const RENDER_TRUE_VALUE = 'true';

    /**
     * @internal
     */
    const RENDER_FALSE_VALUE = 'false';

    /**
     * The icon for a value that is true.
     * Default: null.
     *
     * @var string|null
     */
    protected $trueIcon;

    /**
     * The icon for a value that is false.
     * Default: null.
     *
     * @var string|null
     */
    protected $falseIcon;

    /**
     * The label for a value that is true.
     * Default: null.
     *
     * @var string|null
     */
    protected $trueLabel;

    /**
     * The label for a value that is false.
     * Default: null.
     *
     * @var string|null
     */
    protected $falseLabel;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function renderSingleField(array &$row, array &$resultRow)
    {
        $path = Helper::getDataPropertyPath($this->data);

        if ($this->accessor->isReadable($row, $path)) {
            if (true === $this->isEditableContentRequired($row)) {
                $content = $this->renderTemplate($this->accessor->getValue($row, $path), $row[$this->editable->getPk()]);
            } else {
                $content = $this->renderTemplate($this->accessor->getValue($row, $path));
            }

            $this->accessor->setValue($resultRow, $path, $content);
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function renderToMany(array &$row, array &$resultRow)
    {
        $value = null;
        $path = Helper::getDataPropertyPath($this->data, $value);

        if ($this->accessor->isReadable($row, $path)) {
            $entries = $this->accessor->getValue($row, $path);

            if (\count($entries) > 0) {
                foreach ($entries as $key => $entry) {
                    $currentPath = $path.'['.$key.']'.$value;
                    $currentObjectPath = Helper::getPropertyPathObjectNotation($path, $key, $value);

                    if (true === $this->isEditableContentRequired($row)) {
                        $content = $this->renderTemplate(
                            $this->accessor->getValue($row, $currentPath),
                            $row[$this->editable->getPk()],
                            $currentObjectPath
                        );
                    } else {
                        $content = $this->renderTemplate($this->accessor->getValue($row, $currentPath));
                    }

                    $this->accessor->setValue($resultRow, $currentPath, $content);
                }
            }
            // no placeholder - leave this blank
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCellContentTemplate()
    {
        return '@SgDatatables/render/boolean.html.twig';
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

        $resolver->setDefaults(
            [
                'filter' => [
                    SelectFilter::class,
                    [
                        'search_type' => 'eq',
                        'select_options' => ['' => 'Any', '1' => 'Yes', '0' => 'No'],
                    ],
                ],
                'true_icon' => null,
                'false_icon' => null,
                'true_label' => null,
                'false_label' => null,
                'editable' => null,
            ]
        );

        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('true_icon', ['null', 'string']);
        $resolver->setAllowedTypes('false_icon', ['null', 'string']);
        $resolver->setAllowedTypes('true_label', ['null', 'string']);
        $resolver->setAllowedTypes('false_label', ['null', 'string']);
        $resolver->setAllowedTypes('editable', ['null', 'array']);

        $resolver->setNormalizer('true_label', function (Options $options, $value) {
            if (null === $options['true_icon'] && null === $value) {
                $value = self::RENDER_TRUE_VALUE;
            }

            return $value;
        });

        $resolver->setNormalizer('false_label', function (Options $options, $value) {
            if (null === $options['false_icon'] && null === $value) {
                $value = self::RENDER_FALSE_VALUE;
            }

            return $value;
        });

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * @return string|null
     */
    public function getTrueIcon()
    {
        return $this->trueIcon;
    }

    /**
     * @param string|null $trueIcon
     *
     * @return $this
     */
    public function setTrueIcon($trueIcon)
    {
        $this->trueIcon = $trueIcon;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFalseIcon()
    {
        return $this->falseIcon;
    }

    /**
     * @param string|null $falseIcon
     *
     * @return $this
     */
    public function setFalseIcon($falseIcon)
    {
        $this->falseIcon = $falseIcon;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getTrueLabel()
    {
        return $this->trueLabel;
    }

    /**
     * @param string|null $trueLabel
     *
     * @return $this
     */
    public function setTrueLabel($trueLabel)
    {
        $this->trueLabel = $trueLabel;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getFalseLabel()
    {
        return $this->falseLabel;
    }

    /**
     * @param string|null $falseLabel
     *
     * @return $this
     */
    public function setFalseLabel($falseLabel)
    {
        $this->falseLabel = $falseLabel;

        return $this;
    }

    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Render template.
     *
     * @param string|null $data
     * @param string|null $pk
     * @param string|null $path
     *
     * @return mixed|string
     */
    private function renderTemplate($data, $pk = null, $path = null)
    {
        $renderVars = [
            'data' => $this->isCustomDql() && \in_array($data, [0, 1, '0', '1'], true) ? (bool) $data : $data,
            'default_content' => $this->getDefaultContent(),
            'true_label' => $this->trueLabel,
            'true_icon' => $this->trueIcon,
            'false_label' => $this->falseLabel,
            'false_icon' => $this->falseIcon,
        ];

        // editable vars
        if (null !== $pk) {
            $renderVars = array_merge($renderVars, [
                'column_class_editable_selector' => $this->getColumnClassEditableSelector(),
                'pk' => $pk,
                'path' => $path,
                'empty_text' => $this->editable->getEmptyText(),
            ]);
        }

        return $this->twig->render(
            $this->getCellContentTemplate(),
            $renderVars
        );
    }
}
