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

use Sg\DatatablesBundle\Datatable\Column\AbstractColumn as BaseColumn;

use Exception;

/**
 * Class ArrayColumn
 *
 * @package Sg\DatatablesBundle\Datatable\Column
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
        if (null == $property) {
            throw new Exception("The entity's property can not be null.");
        }

        parent::__construct($property);
    }


    //-------------------------------------------------
    // ColumnInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function getColumnClassName()
    {
        return "array";
    }

    /**
     * {@inheritdoc}
     */
    public function setOptions(array $options)
    {
        parent::setOptions($options);

        if (array_key_exists("read_as", $options)) {
            $this->setData($options["read_as"]);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaults()
    {
        parent::setDefaults();

        $property = $this->getProperty();

        if (false === strstr($property, '.')) {
            throw new Exception("An association is expected.");
        }
    }
}