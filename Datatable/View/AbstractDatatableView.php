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
    private $templating;

    /**
     * The Translator service.
     *
     * @var Translator
     */
    private $translator;

    /**
     * The Router service.
     *
     * @var RouterInterface
     */
    private $router;

    /**
     * A Features instance.
     *
     * @var Features
     */
    private $features;

    /**
     * An Options instance.
     *
     * @var Options
     */
    private $options;

    /**
     * A Multiselect instance.
     *
     * @var Multiselect
     */
    private $multiselect;

    /**
     * A ColumnBuilder instance.
     *
     * @var ColumnBuilder
     */
    private $columnBuilder;

    /**
     * An Ajax instance.
     *
     * @var Ajax
     */
    private $ajax;

    /**
     * Data to use as the display data for the table.
     *
     * @var mixed
     */
    private $data;

    /**
     * The name of style.
     *
     * @var string
     */
    private $style;

    /**
     * Enable or disable individual filtering.
     *
     * @var boolean
     */
    private $individualFiltering;

    /**
     * The name of the Twig template.
     *
     * @var string
     */
    private $template;


    //-------------------------------------------------
    // Ctor.
    //-------------------------------------------------

    /**
     * Ctor.
     *
     * @param TwigEngine      $templating           The templating service
     * @param Translator      $translator           The translator service
     * @param RouterInterface $router               The router service
     * @param array           $defaultLayoutOptions The default layout options
     */
    public function __construct(TwigEngine $templating, Translator $translator, RouterInterface $router, array $defaultLayoutOptions)
    {
        $this->templating = $templating;
        $this->translator = $translator;
        $this->router = $router;

        $this->features = new Features();
        $this->features->setServerSide($defaultLayoutOptions["server_side"]);
        $this->features->setProcessing($defaultLayoutOptions["processing"]);

        $this->options = new Options();
        $this->options->setPageLength($defaultLayoutOptions["page_length"]);

        $this->multiselect = new Multiselect($defaultLayoutOptions["multiselect"]);
        $this->columnBuilder = new ColumnBuilder();
        $this->ajax = new Ajax();

        $this->data = null;
        $this->style = self::BASE_STYLE;
        $this->individualFiltering = $defaultLayoutOptions["individual_filtering"];
        $this->template = $defaultLayoutOptions["template"];
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

        if (true === $this->features->getServerSide()) {
            if ("" === $this->ajax->getUrl()) {
                throw new Exception("The ajax url parameter must be given.");
            }
        } else {
            $options["view_data"] = $this->getData();
        }

        $options["view_features"] = $this->features;
        $options["view_options"] = $this->options;
        $options["view_multiselect"] = $this->multiselect;
        $options["view_columns"] = $this->columnBuilder->getColumns();
        $options["view_ajax"] = $this->ajax;

        $options["view_style"] = $this->style;
        $options["view_individual_filtering"] = $this->individualFiltering;
        $options["view_table_id"] = $this->getName();

        return $this->templating->render($this->template, $options);
    }

    /**
     * {@inheritdoc}
     */
    abstract public function getEntity();

    /**
     * {@inheritdoc}
     */
    public function setAjax($ajax)
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

    /**
     * {@inheritdoc}
     */
    abstract public function getName();


    //-------------------------------------------------
    // Getters && Setters
    //-------------------------------------------------

    /**
     * Get Line transformer
     *
     * @return callable
     */
    public function getLineFormatter()
    {
        return null;
    }

    /**
     * Set Templating.
     *
     * @param TwigEngine $templating
     *
     * @return $this
     */
    public function setTemplating($templating)
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
     * @param Translator $translator
     *
     * @return $this
     */
    public function setTranslator($translator)
    {
        $this->translator = $translator;

        return $this;
    }

    /**
     * Get Translator.
     *
     * @return Translator
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
    public function setRouter($router)
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
    public function setFeatures($features)
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
    public function setOptions($options)
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
     * Set Multiselect.
     *
     * @param Multiselect $multiselect
     *
     * @return $this
     */
    public function setMultiselect($multiselect)
    {
        $this->multiselect = $multiselect;

        return $this;
    }

    /**
     * Get Multiselect.
     *
     * @return Multiselect
     */
    public function getMultiselect()
    {
        return $this->multiselect;
    }

    /**
     * Set ColumnBuilder.
     *
     * @param ColumnBuilder $columnBuilder
     *
     * @return $this
     */
    public function setColumnBuilder($columnBuilder)
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
        $this->individualFiltering = $individualFiltering;

        return $this;
    }

    /**
     * Get IndividualFiltering.
     *
     * @return boolean
     */
    public function getIndividualFiltering()
    {
        return $this->individualFiltering;
    }

    /**
     * Set Template.
     *
     * @param string $template
     *
     * @return $this
     */
    public function setTemplate($template)
    {
        $this->template = $template;

        return $this;
    }

    /**
     * Get Template.
     *
     * @return string
     */
    public function getTemplate()
    {
        return $this->template;
    }
}

