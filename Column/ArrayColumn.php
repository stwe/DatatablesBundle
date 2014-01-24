<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Column;

use Sg\DatatablesBundle\Column\AbstractColumn as BaseColumn;
use Exception;

/**
 * Class ArrayColumn
 *
 * @package Sg\DatatablesBundle\Column
 */
class ArrayColumn extends BaseColumn
{
    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param null|string $property An entity's property
     *
     * @throws Exception
     */
    public function __construct($property = null)
    {
        parent::__construct($property);

        // association delimiter found?
        if (strstr($property, '.') !== false) {
            $fieldsArray = explode('.', $property);
            $prev = array_slice($fieldsArray, count($fieldsArray) - 2, 1);
            $last = array_slice($fieldsArray, count($fieldsArray) - 1, 1);
            $this->setMData($prev[0]);
            $this->setMRender('[, ].' . $last[0]);
        } else {
            throw new Exception('Association expected.');
        }
    }


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getClassName()
    {
        return 'array';
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);
    }
}