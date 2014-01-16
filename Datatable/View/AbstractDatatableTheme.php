<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Datatable\View;

/**
 * Class AbstractDatatableTheme
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
abstract class AbstractDatatableTheme implements DatatableThemeInterface
{
    /**
     * @var mixed
     */
    protected $sDomValues = null;

    /**
     * @var string
     */
    protected $tableClasses;

    /**
     * @var string
     */
    protected $formClasses;

    /**
     * @var null|string
     */
    protected $pagination = null;

    /**
     * @var string
     */
    protected $iconOk;

    /**
     * @var string
     */
    protected $iconRemove;


    //-------------------------------------------------
    // Singleton
    //-------------------------------------------------

    /**
     * @var DatatableThemeInterface
     */
    private static $instance;


    /**
     * Ctor.
     */
    protected function __construct()
    {
    }

    /**
     * Clone.
     */
    final private function __clone()
    {
    }

    /**
     * Get theme instance.
     *
     * @return null|DatatableThemeInterface
     */
    public static function getTheme()
    {
        $className = get_called_class();

        if (!isset(self::$instance )) {
            self::$instance = new $className();
        }

        return self::$instance;
    }


    //-------------------------------------------------
    // DatatableThemeInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    abstract public function getName();

    /**
     * {@inheritdoc}
     */
    abstract public function getTemplate();

    /**
     * {@inheritdoc}
     */
    public function getSDomValues()
    {
        return $this->sDomValues;
    }

    /**
     * {@inheritdoc}
     */
    public function setSDomValues($sDomValues)
    {
        $this->sDomValues = $sDomValues;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getTableClasses()
    {
        return $this->tableClasses;
    }

    /**
     * {@inheritdoc}
     */
    public function getFormClasses()
    {
        return $this->formClasses;
    }

    /**
     * {@inheritdoc}
     */
    public function getPagination()
    {
        return $this->pagination;
    }

    /**
     * {@inheritdoc}
     */
    public function getIconOk()
    {
        return $this->iconOk;
    }

    /**
     * {@inheritdoc}
     */
    public function getIconRemove()
    {
        return $this->iconRemove;
    }
}