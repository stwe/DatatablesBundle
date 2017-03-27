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
     *
     * @param string $prefix
     *
     * @return string
     */
    public static function generateUniqueID($prefix = '')
    {
        $id = sha1(microtime(true).mt_rand(10000, 90000));

        return $prefix ? $prefix.'-'.$id : $id;
    }

    /**
     * Returns a property path for the Accessor.
     *
     * @param string      $data
     * @param null|string $value
     *
     * @return string
     */
    public static function getDataPropertyPath($data, &$value = null)
    {
        // remove all whitespaces from $data
        $data = str_replace(' ', '', $data);

        // handle nested array case
        if (true === is_int(strpos($data, '[,]'))) {
            $before = strstr($data, '[,]', true);
            $value = strstr($data, '[,]', false);
            $value = '['.str_replace('[,].', '', $value).']';
            $data = $before;
        }

        // e.g. 'createdBy.allowed' => [createdBy][allowed]
        return '['.str_replace('.', '][', $data).']';
    }
}
