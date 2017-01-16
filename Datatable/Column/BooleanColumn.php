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
    public function getTemplate()
    {
        return 'SgDatatablesBundle:column:column.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function renderContent(array &$row)
    {
        if (null === $this->trueIcon && null === $this->trueLabel) {
            $this->trueLabel = 'true';
        }

        if (null === $this->falseIcon && null === $this->falseLabel) {
            $this->falseLabel = 'false';
        }

        $row[$this->data] = $this->twig->render(
            'SgDatatablesBundle:render:boolean.html.twig',
            array(
                'data' => $row[$this->data],
                'true_label' => $this->trueLabel,
                'true_icon' => $this->trueIcon,
                'false_label' => $this->falseLabel,
                'false_icon' => $this->falseIcon,
            )
        );
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
            'filter' => array(SelectFilter::class, array(
                'search_type' => 'eq',
                'select_options' => array('' => 'Any', '1' => 'Yes', '0' => 'No')
            )),
            'true_icon' => null,
            'false_icon' => null,
            'true_label' => null,
            'false_label' => null,
            'editable' => false,
            'editable_if' => null,
        ));

        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('true_icon', array('null', 'string'));
        $resolver->setAllowedTypes('false_icon', array('null', 'string'));
        $resolver->setAllowedTypes('true_label', array('null', 'string'));
        $resolver->setAllowedTypes('false_label', array('null', 'string'));
        $resolver->setAllowedTypes('editable', 'bool');
        $resolver->setAllowedTypes('editable_if', array('null', 'Closure'));

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
}
