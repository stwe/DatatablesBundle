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

    /**
     * {@inheritdoc}
     */
    abstract public function getName();

    /**
     * {@inheritdoc}
     */
    abstract public function getTemplate();
}