<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable;

/**
 * Class Helper
 *
 * @package Sg\DatatablesBundle\Datatable
 */
class Helper
{
    /**
     * Generate a unique ID.

     * @param string $prefix
     *
     * @return string
     */
    public static function generateUniqueID($prefix = '')
    {
        $id = sha1(microtime(true) . mt_rand(10000,90000));

        return $prefix ? $prefix . '-' . $id : $id;
    }

    /**
     * Returns a property path for the Accessor.
     *
     * @param string $data
     *
     * @return string
     */
    public static function getDataPropertyPath($data)
    {
        return '[' . str_replace('.', '][', $data) . ']';
    }

}
