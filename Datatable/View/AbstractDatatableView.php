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

use Sg\DatatablesBundle\Column\ColumnBuilder;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Translation\Translator;
use Exception;

/**
 * Class AbstractDatatableView
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
abstract class AbstractDatatableView implements DatatableViewInterface
{
    /**
     * The templating service.
     *
     * @var TwigEngine
     */
    private $templating;

    /**
     * The translator service.
     *
     * @var Translator
     */
    private $translator;

    /**
     * The router service.
     *
     * @var Router
     */
    private $router;

    /**
     * A theme instance.
     *
     * @var DatatableThemeInterface
     */
    private $theme;

    /**
     * Configure DataTables to use server-side processing.
     *
     * @var boolean
     */
    private $bServerSide;

    /**
     * An array of data to use for the table.
     *
     * @var mixed
     */
    private $aaData;

    /**
     * Enable or disable the display of a 'processing' indicator.
     *
     * @var boolean
     */
    private $bProcessing;

    /**
     * Number of rows to display on a single page when using pagination.
     *
     * @var integer
     */
    private $iDisplayLength;

    /**
     * A ColumnBuilder instance.
     *
     * @var ColumnBuilder
     */
    protected $columnBuilder;

    /**
     * The sAjaxSource path.
     *
     * @var string
     */
    private $sAjaxSource;

    /**
     * Array for custom options.
     *
     * @var array
     */
    private $customizeOptions;

    /**
     * A Multiselect instance.
     *
     * @var Multiselect
     */
    protected $multiselect;

    /**
     * Enable or disable individual filtering.
     *
     * @var boolean
     */
    private $individualFiltering;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param TwigEngine $templating           The templating service
     * @param Translator $translator           The translator service
     * @param Router     $router               The router service
     * @param array      $defaultLayoutOptions The default layout options
     */
    public function __construct(TwigEngine $templating, Translator $translator, Router $router, array $defaultLayoutOptions)
    {
        $this->templating = $templating;
        $this->translator = $translator;
        $this->router = $router;
        $this->theme = null;
        $this->bServerSide = $defaultLayoutOptions['server_side'];
        $this->aaData = null;
        $this->bProcessing = $defaultLayoutOptions['processing'];
        $this->iDisplayLength = (int) $defaultLayoutOptions['display_length'];
        $this->columnBuilder = new ColumnBuilder();
        $this->sAjaxSource = '';
        $this->customizeOptions = array();
        $this->multiselect = new Multiselect($defaultLayoutOptions['multiselect']);
        $this->individualFiltering = $defaultLayoutOptions['individual_filtering'];
    }


    //-------------------------------------------------
    // DatatableViewInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    abstract public function buildDatatableView();

    /**
     * {@inheritdoc}
     */
    public function renderDatatableView()
    {
        $options = array();

        // DatatableViewInterface Twig variables

        $options['dt_bServerSide'] = $this->getBServerSide();
        $options['dt_sAjaxSource'] = $this->getSAjaxSource();

        if (true === $options['dt_bServerSide']) {
            if ('' === $options['dt_sAjaxSource']) {
                throw new Exception('The sAjaxSource parameter must be given.');
            }
        } else {
            $options['dt_sAjaxSource'] = '';
            $options['dt_aaData'] = $this->getAaData();
        }

        $options['dt_bProcessing'] = $this->getBProcessing();
        $options['dt_iDisplayLength'] = $this->getIDisplayLength();
        $options['dt_tableId'] = $this->getName();
        $options['dt_columns'] = $this->columnBuilder->getColumns();
        $options['dt_customizeOptions'] = $this->getCustomizeOptions();
        $options['dt_multiselect'] = $this->multiselect;
        $options['dt_individualFiltering'] = $this->getIndividualFiltering();

        // DatatableThemeInterface Twig variables

        if (null === $this->theme) {
            throw new Exception('The datatable needs a theme.');
        }

        $options['theme_name'] = $this->theme->getName();
        $options['theme_sDom'] = $this->theme->getSDom();
        $options['theme_tableClasses'] = $this->theme->getTableClasses();
        $options['theme_formClasses'] = $this->theme->getFormClasses();
        $options['theme_formSubmitButtonClasses'] = $this->theme->getFormSubmitButtonClasses();
        $options['theme_pagination'] = $this->theme->getPagination();

        return $this->templating->render($this->theme->getTemplate(), $options);
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getName();


    //-------------------------------------------------
    // Public
    //-------------------------------------------------

    /**
     * Get translator.
     *
     * @return Translator
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Get router.
     *
     * @return Router
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Set theme.
     *
     * @param DatatableThemeInterface $theme
     *
     * @return $this
     */
    public function setTheme(DatatableThemeInterface $theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * Get theme.
     *
     * @return null|DatatableThemeInterface
     */
    public function getTheme()
    {
        return $this->theme;
    }

    /**
     * Set bServerSide.
     *
     * @param boolean $bServerSide
     *
     * @return $this
     */
    public function setBServerSide($bServerSide)
    {
        $this->bServerSide = (boolean) $bServerSide;

        return $this;
    }

    /**
     * Get bServerSide.
     *
     * @return boolean
     */
    public function getBServerSide()
    {
        return (boolean) $this->bServerSide;
    }

    /**
     * Set aaData.
     *
     * @param mixed $aaData
     *
     * @return $this
     */
    public function setAaData($aaData)
    {
        $this->aaData = $aaData;

        return $this;
    }

    /**
     * Get aaData.
     *
     * @return mixed
     */
    public function getAaData()
    {
        return $this->aaData;
    }

    /**
     * Set bProcessing.
     *
     * @param boolean $bProcessing
     *
     * @return $this
     */
    public function setBProcessing($bProcessing)
    {
        $this->bProcessing = (boolean) $bProcessing;

        return $this;
    }

    /**
     * Get bProcessing.
     *
     * @return boolean
     */
    public function getBProcessing()
    {
        return (boolean) $this->bProcessing;
    }

    /**
     * Set iDisplayLength.
     *
     * @param int $iDisplayLength
     *
     * @return $this
     */
    public function setIDisplayLength($iDisplayLength)
    {
        $this->iDisplayLength = (int) $iDisplayLength;

        return $this;
    }

    /**
     * Get iDisplayLength.
     *
     * @return int
     */
    public function getIDisplayLength()
    {
        return (int) $this->iDisplayLength;
    }

    /**
     * Set sAjaxSource.
     *
     * @param string $sAjaxSource
     *
     * @return $this
     */
    public function setSAjaxSource($sAjaxSource)
    {
        $this->sAjaxSource = $sAjaxSource;

        return $this;
    }

    /**
     * Get sAjaxSource.
     *
     * @return string
     */
    public function getSAjaxSource()
    {
        return $this->sAjaxSource;
    }

    /**
     * Set customizeOptions.
     *
     * @param array $customizeOptions
     *
     * @return $this
     */
    public function setCustomizeOptions(array $customizeOptions)
    {
        $this->customizeOptions = $customizeOptions;

        return $this;
    }

    /**
     * Get customizeOptions.
     *
     * @return array
     */
    public function getCustomizeOptions()
    {
        return $this->customizeOptions;
    }

    /**
     * Set individualFiltering.
     *
     * @param boolean $individualFiltering
     *
     * @return $this
     */
    public function setIndividualFiltering($individualFiltering)
    {
        $this->individualFiltering = (boolean) $individualFiltering;

        return $this;
    }

    /**
     * Get individualFiltering.
     *
     * @return boolean
     */
    public function getIndividualFiltering()
    {
        return (boolean) $this->individualFiltering;
    }
}

