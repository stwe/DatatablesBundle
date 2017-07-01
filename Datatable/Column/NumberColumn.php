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

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class NumberColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
 */
class NumberColumn extends Column
{
    /**
     * A NumberFormatter instance.
     * A required option.
     *
     * @var \NumberFormatter
     */
    protected $formatter;

    /**
     * Use NumberFormatter::formatCurrency instead NumberFormatter::format to format the value.
     * Default: false
     *
     * @var bool
     */
    protected $useFormatCurrency;

    /**
     * The currency code.
     * Default: null => NumberFormatter::INTL_CURRENCY_SYMBOL is used
     *
     * @var null|string
     */
    protected $currency;

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

        $resolver->setRequired('formatter');

        $resolver->setDefaults(
            array(
                'use_format_currency' => false,
                'currency' => null,
            )
        );

        $resolver->setAllowedTypes('formatter', array('object'));
        $resolver->setAllowedTypes('use_format_currency', array('bool'));
        $resolver->setAllowedTypes('currency', array('null', 'string'));

        $resolver->setAllowedValues('formatter', function ($formatter) {
            if (!$formatter instanceof \NumberFormatter) {
                return false;
            }

            return true;
        });

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get formatter.
     *
     * @return \NumberFormatter
     */
    public function getFormatter()
    {
        return $this->formatter;
    }

    /**
     * Set formatter.
     *
     * @param \NumberFormatter $formatter
     *
     * @return $this
     */
    public function setFormatter(\NumberFormatter  $formatter)
    {
        $this->formatter = $formatter;

        return $this;
    }

    /**
     * Get useFormatCurrency.
     *
     * @return bool
     */
    public function isUseFormatCurrency()
    {
        return $this->useFormatCurrency;
    }

    /**
     * Set useFormatCurrency.
     *
     * @param bool $useFormatCurrency
     *
     * @return $this
     */
    public function setUseFormatCurrency($useFormatCurrency)
    {
        $this->useFormatCurrency = $useFormatCurrency;

        return $this;
    }

    /**
     * Get currency.
     *
     * @return null|string
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Set currency.
     *
     * @param null|string $currency
     *
     * @return $this
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;

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
        if (true === $this->useFormatCurrency) {
            if (false === is_float($data)) {
                $data = (float) $data;
            }

            if (null === $this->currency) {
                $this->currency = $this->formatter->getSymbol(\NumberFormatter::INTL_CURRENCY_SYMBOL);
            }

            $data = $this->formatter->formatCurrency($data, $this->currency);
        } else {
            // expected number (int or float), other values will be converted to a numeric value
            $data = $this->formatter->format($data);
        }

        return $this->twig->render(
            $this->getCellContentTemplate(),
            array(
                'data' => $data,
                'column_class_editable_selector' => $this->getColumnClassEditableSelector(),
                'pk' => $pk,
                'path' => $path,
            )
        );
    }
}
