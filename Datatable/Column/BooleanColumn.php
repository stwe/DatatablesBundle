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

use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Editable\EditableInterface;
use Sg\DatatablesBundle\Datatable\Helper;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class BooleanColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class BooleanColumn extends AbstractColumn
{
    /**
     * This Column is editable.
     */
    use EditableTrait;

    /**
     * The Column is filterable.
     */
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
     * Default: null
     *
     * @var null|string
     */
    protected $trueIcon;

    /**
     * The icon for a value that is false.
     * Default: null
     *
     * @var null|string
     */
    protected $falseIcon;

    /**
     * The label for a value that is true.
     * Default: null
     *
     * @var null|string
     */
    protected $trueLabel;

    /**
     * The label for a value that is false.
     * Default: null
     *
     * @var null|string
     */
    protected $falseLabel;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function renderSingleField(array &$row)
    {
        $path = Helper::getDataPropertyPath($this->data);

        if (true === $this->isEditableContentRequired($row)) {
            $content = $this->renderTemplate($this->accessor->getValue($row, $path), $row[$this->editable->getPk()]);
        } else {
            $content = $this->renderTemplate($this->accessor->getValue($row, $path));
        }

        $this->accessor->setValue($row, $path, $content);

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function renderToMany(array &$row)
    {
        $value = null;
        $path = Helper::getDataPropertyPath($this->data, $value);

        $entries = $this->accessor->getValue($row, $path);

        if (count($entries) > 0) {
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

                $this->accessor->setValue($row, $currentPath, $content);
            }
        } else {
            // no placeholder - leave this blank
        }

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getCellContentTemplate()
    {
        return 'SgDatatablesBundle:render:boolean.html.twig';
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
                    'column_dql' => $this->dql,
                    'original_type_of_field' => $this->getOriginalTypeOfField(),
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

        $resolver->setDefaults(
            array(
                'filter' => array(
                    SelectFilter::class,
                    array(
                        'search_type' => 'eq',
                        'select_options' => array('' => 'Any', '1' => 'Yes', '0' => 'No'),
                    ),
                ),
                'true_icon' => null,
                'false_icon' => null,
                'true_label' => null,
                'false_label' => null,
                'editable' => null,
            )
        );

        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('true_icon', array('null', 'string'));
        $resolver->setAllowedTypes('false_icon', array('null', 'string'));
        $resolver->setAllowedTypes('true_label', array('null', 'string'));
        $resolver->setAllowedTypes('false_label', array('null', 'string'));
        $resolver->setAllowedTypes('editable', array('null', 'array'));

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
     * Get trueIcon.
     *
     * @return null|string
     */
    public function getTrueIcon()
    {
        return $this->trueIcon;
    }

    /**
     * Set trueIcon.
     *
     * @param null|string $trueIcon
     *
     * @return $this
     */
    public function setTrueIcon($trueIcon)
    {
        $this->trueIcon = $trueIcon;

        return $this;
    }

    /**
     * Get falseIcon.
     *
     * @return null|string
     */
    public function getFalseIcon()
    {
        return $this->falseIcon;
    }

    /**
     * Set falseIcon.
     *
     * @param null|string $falseIcon
     *
     * @return $this
     */
    public function setFalseIcon($falseIcon)
    {
        $this->falseIcon = $falseIcon;

        return $this;
    }

    /**
     * Get trueLabel.
     *
     * @return null|string
     */
    public function getTrueLabel()
    {
        return $this->trueLabel;
    }

    /**
     * Set trueLabel.
     *
     * @param null|string $trueLabel
     *
     * @return $this
     */
    public function setTrueLabel($trueLabel)
    {
        $this->trueLabel = $trueLabel;

        return $this;
    }

    /**
     * Get falseLabel.
     *
     * @return null|string
     */
    public function getFalseLabel()
    {
        return $this->falseLabel;
    }

    /**
     * Set falseLabel.
     *
     * @param null|string $falseLabel
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
        $renderVars = array(
            'data' => $data,
            'default_content' => $this->getDefaultContent(),
            'true_label' => $this->trueLabel,
            'true_icon' => $this->trueIcon,
            'false_label' => $this->falseLabel,
            'false_icon' => $this->falseIcon,
        );

        // editable vars
        if (null !== $pk) {
            $renderVars = array_merge($renderVars, array(
                'column_class_editable_selector' => $this->getColumnClassEditableSelector(),
                'pk' => $pk,
                'path' => $path,
                'empty_text' => $this->editable->getEmptyText(),
            ));
        }

        return $this->twig->render(
            $this->getCellContentTemplate(),
            $renderVars
        );
    }
}
