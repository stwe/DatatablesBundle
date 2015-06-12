<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Command;

/**
 * Class Fields
 *
 * @package Sg\DatatablesBundle\Command
 */
class Fields
{
    /**
     * Parse fields.
     *
     * @param string $input
     *
     * @return array
     */
    public static function parseFields($input)
    {
        $fields = array();

        foreach (explode(' ', $input) as $value) {
            $elements = explode(':', $value);
            $property = $elements[0];
            if (strlen($property)) {
                $columnName = isset($elements[1]) ? $elements[1] : 'column';
                preg_match_all('/(.*)\((.*)\)/', $columnName, $matches);
                $columnName = isset($matches[1][0]) ? $matches[1][0] : $columnName;

                $title = ucwords(str_replace('.', ' ', $property));

                $row = array();
                $row['property'] = $property;
                $row['column_name'] = $columnName;
                $row['title'] = $title;
                $fields[] = $row;
            }
        }

        return $fields;
    }
}
