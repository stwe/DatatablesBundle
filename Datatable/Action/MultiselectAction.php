<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\Action;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MultiselectAction
 *
 * @package Sg\DatatablesBundle\Datatable\Action
 */
class MultiselectAction extends Action
{
    /**
     * Name of datatable view.
     *
     * @var string
     */
    protected $tableName;

    //-------------------------------------------------
    // OptionsInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $tableName = $this->tableName;
        $resolver->setNormalizer('attributes', function($options, $value) use($tableName) {
            $baseClass = $tableName . '_multiselect_action_click';
            $value['class'] = array_key_exists('class', $value) ? ($value['class'] . ' ' . $baseClass) : $baseClass;

            return $value;
        });

        return $this;
    }

    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set table name.
     *
     * @param string $tableName
     *
     * @return $this
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;

        return $this;
    }

    /**
     * Get table name.
     *
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }
}
