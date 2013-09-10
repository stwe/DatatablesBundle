# An example

1. Create your layout.html.twig

    ``` html
    {% extends '::base.html.twig' %}

    {% block title %}UserBundle{% endblock %}

    {% block stylesheets %}

        {% stylesheets
            '@bootstrap_css'
            'bundles/sgdatatables/css/dataTables_bootstrap.css'
            filter='cssrewrite' %}
            <link href="{{ asset_url }}" rel="stylesheet" type="text/css"/>
        {% endstylesheets %}

    {% endblock %}

    {% block body%}

        {% block scripts %}

            {% javascripts
                '@jquery_js'
                '@bootstrap_js'
                '@datatables_js'
                'bundles/sgdatatables/js/dataTables_bootstrap.js'
                'bundles/fosjsrouting/js/router.js' %}
                <script type="text/javascript" src="{{ asset_url }}"></script>
            {% endjavascripts %}

            <script src="{{ path('fos_js_routing_js', {"callback": "fos.Router.setData"}) }}"></script>

        {% endblock %}

        <div class="container">
            {% block content %}
            {% endblock %}
        </div>

    {% endblock %}
    ```

2. Create your Datatables class

    ``` php
    <?php

    namespace Sg\UserBundle\Datatables;

    use Sg\DatatablesBundle\Datatable\AbstractDatatableView;
    use Sg\DatatablesBundle\Column\Column;
    use Sg\DatatablesBundle\Column\ActionColumn;

    /**
     * Class UserDatatable
     *
     * @package Sg\UserBundle\Datatables
     */
    class UserDatatable extends AbstractDatatableView
    {
        /**
         * {@inheritdoc}
         */
        public function build()
        {
            $this->setTableId('user_datatable');
            $this->setSAjaxSource('user_results');

            $this->setTableHeaders(array(
                'Username',
                'Email',
                'Enabled',
                'Posts',
                ''
            ));

            $nameField = new Column('username');

            $emailField = new Column('email');

            $enabledField = new Column('enabled');
            $enabledField->setBSearchable('false');
            $enabledField->setSWidth('90');
            $enabledField->setMRender("render_boolean_icons(data, type, full)");

            $postsField = new Column('posts');
            $postsField->setRenderArray(true);
            $postsField->setRenderArrayFieldName('title');
            $postsField->setSName('posts.title');


            $actionField = new ActionColumn('id');
            // the edit route: @Route("/{id}/show", name="user_edit", options={"expose"=true})
            // important here is the section: options={"expose"=true}
            $actionField->setRoute('user_edit');
            $actionField->addRouteParameter('id', 'id');

            // you can set multiple parameters:
            //$actionField->addRouteParameter('param1', 'email');
            //$actionField->addRouteParameter('param2', 'username');

            // a Bootstrap2 tooltip
            $actionField->addAttribute('rel', 'tooltip');
            $actionField->addAttribute('title', 'Test Tooltip');

            // set a label
            $actionField->setLabel('TestLabel');

            // ... or an icon
            //$actionField->setIcon(ActionColumn::DEFAULT_EDIT_ICON);

            // set the action field width
            $actionField->setSWidth('92');

            $this->addColumn($nameField);
            $this->addColumn($emailField);
            $this->addColumn($enabledField);
            $this->addColumn($postsField);
            $this->addActionColumn($actionField);
        }
    }
    ```

3. Create your index.html.twig

    ``` html
    {% extends 'SgUserBundle::layout.html.twig' %}

    {% block title %}{{ title }}{% endblock %}

    {% block content %}

        <h2>{{ title }}</h2>

        {{ datatable_render(datatable) }}

    {% endblock %}
    ```

4. Add controller actions

    ``` php
    /**
     * Lists all User entities.
     *
     * @Route("/", name="user")
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function indexAction()
    {
        /**
         * @var \Sg\DatatablesBundle\Factory\DatatableFactory $factory
         */
        $factory = $this->get('sg_datatables.factory');

        /**
         * @var \Sg\DatatablesBundle\Datatable\AbstractDatatableView $datatableView
         */
        $datatableView = $factory->getDatatableView('Sg\UserBundle\Datatables\UserDatatable');

        return array(
            'title' => 'Enabled Users',
            'datatable' => $datatableView,
        );
    }

    /**
     * Get all User entities.
     *
     * @Route("/results", name="user_results")
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexResultsAction()
    {
        /**
         * @var \Sg\DatatablesBundle\Datatable\DatatableData $datatable
         */
        $datatable = $this->get('sg_datatables.datatable')->getDatatable('SgUserBundle:User');

        /**
         * @var \Doctrine\ORM\QueryBuilder $qb
         */
        $callbackFunction =

            function($qb)
            {
                $andExpr = $qb->expr()->andX();
                $andExpr->add($qb->expr()->eq('fos_user.enabled', '1'));
                $qb->andWhere($andExpr);
            };

        $datatable->addWhereBuilderCallback($callbackFunction);

        return $datatable->getSearchResults();
    }
    ```

5. Working with Associations

    We extend the user entity to multiple OneToMany associations (Post and Comment).

    ``` php
    <?php

    namespace Sg\UserBundle\Entity;

    use Doctrine\Common\Collections\ArrayCollection;
    use FOS\UserBundle\Model\User as BaseUser;
    use Doctrine\ORM\Mapping as ORM;
    use Sg\AppBundle\Entity\Comment;
    use Sg\AppBundle\Entity\Post;

    /**
     * Class User
     *
     * @ORM\Entity
     * @ORM\Table(name="fos_user")
     *
     * @package Sg\UserBundle\Entity
     */
    class User extends BaseUser
    {
        /**
         * @ORM\Id
         * @ORM\Column(type="integer")
         * @ORM\GeneratedValue(strategy="AUTO")
         */
        protected $id;

        /**
         * @ORM\OneToMany(
         *     targetEntity="Sg\AppBundle\Entity\Post",
         *     mappedBy="createdBy"
         * )
         */
        private $posts;

        /**
         * @ORM\OneToMany(
         *     targetEntity="Sg\AppBundle\Entity\Comment",
         *     mappedBy="createdBy"
         * )
         */
        private $comments;


        /**
         * Ctor.
         */
        public function __construct()
        {
            parent::__construct();

            // your own logic

            $this->posts = new ArrayCollection();
            $this->comments = new ArrayCollection();
        }

        // ...

    }
    ```

    For a comma-separated view of all blog titles we add the following to the UserDatatable class.

    ``` php
    $this->setTableHeaders(array(
        'Username',
        'Email',
        'Enabled',
        'Posts',
        ''
    ));

    $postsField = new Field('posts');
    $postsField->setRenderArray(true);
    $postsField->setRenderArrayFieldName('title');
    $postsField->setSName('posts.title');

    $this->addField($nameField);
    $this->addField($emailField);
    $this->addField($enabledField);
    $this->addField($postsField);
    $this->addField($idField);
    ```

    The result should looks like that:

    <div style="text-align:center"><img alt="Screenshot" src="https://github.com/stwe/DatatablesBundle/raw/master/Resources/doc/screenshot2.jpg"></div>
