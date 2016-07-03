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
use Closure;

/**
 * Class MultiselectAction
 *
 * @package Sg\DatatablesBundle\Datatable\Action
 */
class MultiselectAction extends Action
{
    /**
     * Render checkbox only if conditions are True.
     * @todo: implement (need access to $row)
     *
     * @var Closure
     */
    protected $renderCheckboxIf;

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

        // @todo: implement (need access to $row)
        //$resolver->remove(array('render_if'));

        $resolver->setDefaults(array(
            'render_checkbox_if' => null
        ));

        $resolver->setAllowedTypes('render_checkbox_if', array('Closure', 'null'));

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
     * Set renderCheckboxIf.
     *
     * @param Closure $renderCheckboxIf
     *
     * @return $this
     */
    public function setRenderCheckboxIf($renderCheckboxIf)
    {
        $this->renderCheckboxIf = $renderCheckboxIf;

        return $this;
    }

    /**
     * Get renderCheckboxIf.
     *
     * @return Closure
     */
    public function getRenderCheckboxIf()
    {
        return $this->renderCheckboxIf;
    }

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
