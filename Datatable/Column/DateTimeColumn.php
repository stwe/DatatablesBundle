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

use Exception;
use Sg\DatatablesBundle\Datatable\Editable\EditableInterface;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Helper;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DateTimeColumn extends AbstractColumn
{
    use EditableTrait;

    use FilterableTrait;

    /**
     * Moment.js date format.
     * Default: 'lll'.
     *
     * @see http://momentjs.com/
     *
     * @var string
     */
    protected $dateFormat;

    /**
     * Use the time ago format.
     * Default: false.
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

            if (null !== $entries && \count($entries) > 0) {
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
        return '@SgDatatables/render/datetime.html.twig';
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
            'date_format' => 'lll',
            'timeago' => false,
            'filter' => [TextFilter::class, []],
            'editable' => null,
        ]);

        $resolver->setAllowedTypes('date_format', 'string');
        $resolver->setAllowedTypes('timeago', 'bool');
        $resolver->setAllowedTypes('filter', 'array');
        $resolver->setAllowedTypes('editable', ['null', 'array']);

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
     * @throws Exception
     *
     * @return $this
     */
    public function setDateFormat($dateFormat)
    {
        if (empty($dateFormat) || ! \is_string($dateFormat)) {
            throw new Exception('DateTimeColumn::setDateFormat(): A non-empty string is expected.');
        }

        $this->dateFormat = $dateFormat;

        return $this;
    }

    /**
     * @return bool
     */
    public function isTimeago()
    {
        return $this->timeago;
    }

    /**
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
            'data' => $data,
            'default_content' => $this->getDefaultContent(),
            'date_format' => $this->dateFormat,
            'timeago' => $this->timeago,
            'datatable_name' => $this->getDatatableName(),
            'row_id' => Helper::generateUniqueID(),
        ];

        // editable vars
        if (null !== $pk) {
            $renderVars = array_merge($renderVars, [
                'column_class_editable_selector' => $this->getColumnClassEditableSelector(),
                'pk' => $pk,
                'path' => $path,
            ]);
        }

        return $this->twig->render(
            $this->getCellContentTemplate(),
            $renderVars
        );
    }
}
