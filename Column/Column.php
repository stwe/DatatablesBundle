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

/**
 * Class Column
 *
 * @package Sg\DatatablesBundle\Column
 */
class Column extends BaseColumn
{
    /**
     * Association flag.
     *
     * @var boolean
     */
    protected $isAssociation;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param string $name
     */
    public function __construct($name = null)
    {
        parent::__construct($name);

        // association delimiter found?
        if (strstr($name, '.') !== false) {
            $this->isAssociation = true;
            $fieldsArray = explode('.', $name);
            $prev = array_slice($fieldsArray, count($fieldsArray) - 2, 1);
            $last = array_slice($fieldsArray, count($fieldsArray) - 1, 1);
            $this->mData = $prev[0];
            $this->mRender = '[, ].' . $last[0];
        } else {
            // no association found
            $this->isAssociation = false;
            $this->mData = $name;
            $this->mRender = null;
        }
    }


    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * @return boolean
     */
    public function getIsAssociation()
    {
        return $this->isAssociation;
    }
}