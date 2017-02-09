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
use Sg\DatatablesBundle\Datatable\UniqueID;

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
    public function getTemplate()
    {
        return 'SgDatatablesBundle:column:column.html.twig';
    }

    /**
     * {@inheritdoc}
     */
    public function renderCellContent(array &$row)
    {
        $row[$this->data] = $this->twig->render(
            'SgDatatablesBundle:render:datetime.html.twig',
            array(
                'datatable_name' => $this->getDatatableName(),
                'row_id' => UniqueID::generateUniqueID(),
                'data' => $row[$this->data],
                'date_format' => $this->dateFormat,
                'timeago' => $this->timeago
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
}
