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

class ArrayColumn extends Column
{
    /**
     * {@inheritdoc}
     */
    public function renderSingleField(array &$row, array &$resultRow)
    {
        $row[$this->data] = $this->arrayToString($row[$this->data] ?? []);

        return parent::renderSingleField($row, $resultRow);
    }

    /**
     * @param int $tab
     */
    protected function arrayToString(array $array, $tab = 0): string
    {
        $arrayField = '';
        $isArrayAssociative = $this->isAssociative($array);
        foreach ($array as $key => $arrayElement) {
            for ($i = 0; $i < $tab; ++$i) {
                $arrayField .= '&nbsp&nbsp';
            }

            if ($isArrayAssociative) {
                $arrayField .= $key.': ';
            }

            if (\is_array($arrayElement)) {
                $arrayField .= '[<br/>';
                $arrayField .= $this->arrayToString($arrayElement, $tab + 1);
                $arrayField .= ']<br/>';

                continue;
            }

            if ($arrayElement instanceof \DateTime) {
                $arrayField .= $arrayElement->format('Y-m-d').'<br/>';

                continue;
            }

            $arrayField .= $arrayElement.'<br/>';
        }

        return $arrayField;
    }

    protected function isAssociative(array $array): bool
    {
        if (empty($array)) {
            return false;
        }

        return array_keys($array) !== range(0, \count($array) - 1);
    }
}
