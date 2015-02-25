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

use Sg\DatatablesBundle\Datatable\Column\ColumnBuilder;

use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Exception;

/**
 * Class AbstractDatatableView
 *
 * @package Sg\DatatablesBundle\Datatable\View
 */
abstract class AbstractDatatableView implements DatatableViewInterface
{
    /**
     * Default style.
     *
     * @var string
     */
    const BASE_STYLE = "display";

    /**
     * Default style with none of the additional feature style classes.
     *
     * @var string
     */
    const BASE_STYLE_NO_CLASSES = "";

    /**
     * Default style with row border.
     *
     * @var string
     */
    const BASE_STYLE_ROW_BORDERS = "row-border";

    /**
     * Default style with cell border.
     *
     * @var string
     */
    const BASE_STYLE_CELL_BORDERS = "cell-border";

    /**
     * Default style with hover class.
     *
     * @var string
     */
    const BASE_STYLE_HOVER = "hover";

    /**
     * Default style with order-column class.
     *
     * @var string
     */
    const BASE_STYLE_ORDER_COLUMN = "order-column";

    /**
     * Default style with stripe class.
     *
     * @var string
     */
    const BASE_STYLE_STRIPE = "stripe";

    /**
     * jQuery UI's ThemeRoller styles.
     *
     * @var string
     */
    const JQUERY_UI_STYLE = "display";

    /**
     * Bootstrap's table styling options.
     *
     * @var string
     */
    const BOOTSTRAP_3_STYLE = "table table-striped table-bordered";

    /**
     * Foundations's table styling options.
     *
     * @var string
     */
    const FOUNDATION_STYLE = "display";

    /**
     * The Templating service.
     *
     * @var TwigEngine
     */
    protected $templating;

    /**
     * The Translator service.
     *
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * The Router service.
     *
     * @var RouterInterface
     */
    protected $router;

    /**
     * A Features instance.
     *
     * @var Features
     */
    protected $features;

    /**
     * An Options instance.
     *
     * @var Options
     */
    protected $options;

    /**
     * A ColumnBuilder instance.
     *
     * @var ColumnBuilder
     */
    protected $columnBuilder;

    /**
     * An Ajax instance.
     *
     * @var Ajax
     */
    protected $ajax;

    /**
     * Data to use as the display data for the table.
     *
     * @var mixed
     */
    protected $data;

    /**
     * The name of style.
     *
     * @var string
     */
    protected $style;

    /**
     * Enable or disable individual filtering.
     *
     * @var boolean
     */
    protected $individualFiltering;

    /**
     * The Twig templates.
     *
     * @var array
     */
    protected $templates;

    /**
     * DataTables provides direct integration support (https://github.com/DataTables/Plugins/tree/master/integration) for:
     * - Bootstrap
     * - Foundation
     * - jQuery UI
     *
     * This flag is set in the layout, the "dom" and "renderer" options for the integration plugin (i.e. bootstrap).
     *
     * @var boolean
     */
    protected $useIntegrationOptions;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param TwigEngine          $templating           The templating service
     * @param TranslatorInterface $translator           The translator service
     * @param RouterInterface     $router               The router service
     * @param array               $defaultLayoutOptions The default layout options
     */
    public function __construct(TwigEngine $templating, TranslatorInterface $translator, RouterInterface $router, array $defaultLayoutOptions)
    {
        $this->templating = $templating;
        $this->translator = $translator;
        $this->router = $router;

        $this->features = new Features();
        $this->features->setServerSide($defaultLayoutOptions["server_side"]);
        $this->features->setProcessing($defaultLayoutOptions["processing"]);

        $this->options = new Options();
        $this->options->setPageLength($defaultLayoutOptions["page_length"]);

        $this->columnBuilder = new ColumnBuilder();

        $this->ajax = new Ajax();

        $this->data = null;
        $this->style = self::BASE_STYLE;
        $this->individualFiltering = $defaultLayoutOptions["individual_filtering"];
        $this->templates = $defaultLayoutOptions["templates"];
        $this->useIntegrationOptions = false;
    }


    //-------------------------------------------------
    // DatatableViewInterface
    //-------------------------------------------------

    /**
     * {@inheritdoc}
     */
    public function renderDatatableView($type = 'all')
    {
        $options = array();

        if (true === $this->features->getServerSide()) {
            if ("" === $this->ajax->getUrl()) {
                throw new Exception("The ajax url parameter must be given.");
            }
        } else {
            $options["view_data"] = $this->getData();
        }

        $options["view_features"] = $this->features;
        $options["view_options"] = $this->options;
        $options["view_columns"] = $this->columnBuilder->getColumns();
        $options["view_ajax"] = $this->ajax;

        $options["view_style"] = $this->style;
        $options["view_individual_filtering"] = $this->individualFiltering;

        $options["view_multiselect"] = $this->columnBuilder->isMultiselect();
        $options["view_multiselect_column"] = $this->columnBuilder->getMultiselectColumn();

        $options["view_table_id"] = $this->getName();

        $options["view_use_integration_options"] = $this->useIntegrationOptions;

        switch($type) {
            case 'html':
                return $this->templating->render($this->templates['html'], $options);
                break;
            case 'js':
                return $this->templating->render($this->templates['js'], $options);
                break;
            case 'jsns':
                return $this->templating->render($this->templates['jsns'], $options);
                break;
            default:
                return $this->templating->render($this->templates['base'], $options);
                break;
        }

    }

    /**
     * {@inheritdoc}
     */
    public function setAjax(Ajax $ajax)
    {
        $this->ajax = $ajax;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getAjax()
    {
        return $this->ajax;
    }


    //-------------------------------------------------
    // Callable
    //-------------------------------------------------

    /**
     * Get Line Formatter.
     *
     * @return callable
     */
    public function getLineFormatter()
    {
        return null;
    }


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Set Templating.
     *
     * @param TwigEngine $templating
     *
     * @return $this
     */
    public function setTemplating(TwigEngine $templating)
    {
        $this->templating = $templating;

        return $this;
    }

    /**
     * Get Templating.
     *
     * @return TwigEngine
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * Set Translator.
     *
     * @param TranslatorInterface $translator
     *
     * @return $this
     */
    public function setTranslator(TranslatorInterface $translator)
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * Get Translator.
     *
     * @return TranslatorInterface
     */
    public function getTranslator()
    {
        return $this->translator;
    }

    /**
     * Set Router.
     *
     * @param RouterInterface $router
     *
     * @return $this
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;

        return $this;
    }

    /**
     * Get Router.
     *
     * @return RouterInterface
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * Set Features.
     *
     * @param Features $features
     *
     * @return $this
     */
    public function setFeatures(Features $features)
    {
        $this->features = $features;

        return $this;
    }

    /**
     * Get Features.
     *
     * @return Features
     */
    public function getFeatures()
    {
        return $this->features;
    }

    /**
     * Set Options.
     *
     * @param Options $options
     *
     * @return $this
     */
    public function setOptions(Options $options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * Get Options.
     *
     * @return Options
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set ColumnBuilder.
     *
     * @param ColumnBuilder $columnBuilder
     *
     * @return $this
     */
    public function setColumnBuilder(ColumnBuilder $columnBuilder)
    {
        $this->columnBuilder = $columnBuilder;

        return $this;
    }

    /**
     * Get ColumnBuilder.
     *
     * @return ColumnBuilder
     */
    public function getColumnBuilder()
    {
        return $this->columnBuilder;
    }

    /**
     * Set Data.
     *
     * @param mixed $data
     *
     * @return $this
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get Data.
     *
     * @return mixed
     */
    public function getData()
    {
        return $this->data;
    }

    /**
     * Set Style.
     *
     * @param string $style
     *
     * @return $this
     */
    public function setStyle($style)
    {
        switch ($style) {
            case self::JQUERY_UI_STYLE:
                $this->useIntegrationOptions = true;
                break;
            case self::BOOTSTRAP_3_STYLE:
                $this->useIntegrationOptions = true;
                break;
            case self::FOUNDATION_STYLE:
                $this->useIntegrationOptions = true;
                break;
        }

        $this->style = $style;

        return $this;
    }

    /**
     * Get Style.
     *
     * @return string
     */
    public function getStyle()
    {
        return $this->style;
    }

    /**
     * Set IndividualFiltering.
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
     * Get IndividualFiltering.
     *
     * @return boolean
     */
    public function getIndividualFiltering()
    {
        return (boolean) $this->individualFiltering;
    }

    /**
     * Set templates.
     *
     * @param array $templates
     *
     * @return $this
     */
    public function setTemplates($templates)
    {
        $this->templates = $templates;

        return $this;
    }

    /**
     * Get templates.
     *
     * @return array
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * Set useIntegrationsOptions.
     *
     * @param boolean $useIntegrationOptions
     *
     * @return $this
     */
    public function setUseIntegrationOptions($useIntegrationOptions)
    {
        $this->useIntegrationOptions = $useIntegrationOptions;

        return $this;
    }

    /**
     * Get useIntegrationsOptions.
     *
     * @return boolean
     */
    public function getUseIntegrationOptions()
    {
        return $this->useIntegrationOptions;
    }
}
