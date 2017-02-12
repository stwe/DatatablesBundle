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
use Sg\DatatablesBundle\Datatable\Editable\EditableInterface;
use Sg\DatatablesBundle\Datatable\Helper;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Exception;

/**
 * Class DateTimeColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class DateTimeColumn extends AbstractColumn
{
    /**
     * This Column is editable.
     */
    use EditableTrait;

    /**
     * Moment.js date format.
     * Default: 'lll'
     *
     * @link http://momentjs.com/
     *
     * @var string
     */
    protected $dateFormat;

    /**
     * Use the time ago format.
     * Default: false
     *
     * @var bool
     */
    protected $timeago;

    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function renderCellContent(array &$row)
    {
        if (false === $this->isAssociation()) {
            $path = Helper::getDataPropertyPath($this->data);
            $render = $this->getBaseRenderVars($row, $path);

            if ($this->editable instanceof EditableInterface && true === $this->editable->callEditableIfClosure($row)) {
                $render = array_merge($render, array(
                    'column_class_editable_selector' => $this->getColumnClassEditableSelector(),
                    'pk' => $row[$this->editable->getPk()]
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
                        'pk' => $row[$this->editable->getPk()]
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
        return 'SgDatatablesBundle:render:datetime.html.twig';
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
            'date_format' => 'lll',
            'timeago' => false,
            'filter' => array(TextFilter::class, array()),
            'editable' => null,
        ));

        $resolver->setAllowedTypes('date_format', 'string');
        $resolver->setAllowedTypes('timeago', 'bool');
        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('editable', array('null', 'array'));

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get date format.
     *
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * Set date format.
     *
     * @param string $dateFormat
     *
     * @return $this
     * @throws Exception
     */
    public function setDateFormat($dateFormat)
    {
        if (empty($dateFormat) || !is_string($dateFormat)) {
            throw new Exception('DateTimeColumn::setDateFormat(): A non-empty string is expected.');
        }

        $this->dateFormat = $dateFormat;

        return $this;
    }

    /**
     * Get timeago.
     *
     * @return bool
     */
    public function isTimeago()
    {
        return $this->timeago;
    }

    /**
     * Set timeago.
     *
     * @param bool $timeago
     *
     * @return $this
     */
    public function setTimeago($timeago)
    {
        $this->timeago = $timeago;

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
            'date_format' => $this->dateFormat,
            'timeago' => $this->timeago,
            'datatable_name' => $this->getDatatableName(),
            'row_id' => Helper::generateUniqueID(),
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
