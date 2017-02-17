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
    public function renderCellContent(array &$row)
    {
        if (null === $this->trueIcon && null === $this->trueLabel) {
            $this->trueLabel = 'true';
        }

        if (null === $this->falseIcon && null === $this->falseLabel) {
            $this->falseLabel = 'false';
        }

        if (false === $this->isAssociation()) {
            $path = Helper::getDataPropertyPath($this->data);
            $render = $this->getBaseRenderVars($row, $path);

            if ($this->editable instanceof EditableInterface && true === $this->editable->callEditableIfClosure($row)) {
                $render = array_merge($render, array(
                    'column_class_editable_selector' => $this->getColumnClassEditableSelector(),
                    'pk' => $row[$this->editable->getPk()],
                ));
            }

            $this->renderContent($row, $render, $path);
        } else {
            $toMany = strpos($this->data, ',');

            if (false === $toMany) {
                $path = Helper::getDataPropertyPath($this->data);
                $render = $this->getBaseRenderVars($row, $path);

                if ($this->editable instanceof EditableInterface && true === $this->editable->callEditableIfClosure($row)) {
                    $render = array_merge($render, array(
                        'column_class_editable_selector' => $this->getColumnClassEditableSelector(),
                        'pk' => $row[$this->editable->getPk()],
                        'empty_text' => $this->editable->getEmptyText(),
                    ));
                }

                $this->renderContent($row, $render, $path);
            } else {
                // @todo: content for toMany associations
            }
        }
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
     * Get base render vars.
     *
     * @param array  $row
     * @param string $path
     *
     * @return array
     */
    private function getBaseRenderVars(array $row, $path)
    {
        return array(
            'data' => $this->accessor->getValue($row, $path),
            'default_content' => $this->getDefaultContent(),
            'true_label' => $this->trueLabel,
            'true_icon' => $this->trueIcon,
            'false_label' => $this->falseLabel,
            'false_icon' => $this->falseIcon,
        );
    }

    /**
     * Render content.
     *
     * @param array  $row
     * @param array  $renderVars
     * @param string $path
     *
     * @return $this
     */
    private function renderContent(array &$row, array $renderVars, $path)
    {
        $content = $this->twig->render(
            $this->getCellContentTemplate(),
            $renderVars
        );

        $this->accessor->setValue($row, $path, $content);

        return $this;
    }
}
