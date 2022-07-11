<?= "<?php" . PHP_EOL ?>

namespace <?= $namespace ?>;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;

<?php foreach ($column_types as $type): ?>
use Sg\DatatablesBundle\Datatable\Column\<?= $type ?>;
<?php endforeach; ?>

/**
 * Class <?= $class_name.PHP_EOL ?>
 *
 * @package <?= $namespace ?>\Datatables
 */
class <?= $class_name ?> extends AbstractDatatable
{

    /**
    * {@inheritdoc}
    *
    * @throws \Exception
    */
    public function buildDatatable(array $options = array())
    {
        $this->language->set(array(
            'cdn_language_by_locale' => true
            //'language' => 'de'
        ));

        $this->ajax->set(array(
        ));

        $this->options->set(array(
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order_cells_top' => true,
        ));

        $this->features->set(array(
        ));

        $this->columnBuilder
    <?php foreach ($fields as $field): ?>
        ->add('<?= $field['property'] ?>', <?= $field['column_type'] ?>, array(
                'title' => '<?= $field['title'] ?>',
    <?php if (isset($field['data']) && $field['data']!== null ): ?>
            'data' => '<?= $field['data'] ?>'
    <?php endif ?>
        ))
    <?php endforeach; ?>
        ->add(null, ActionColumn::class, array(
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => array(
                    array(
                        'route' => '<?= $route_pref ?>_show',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('sg.datatables.actions.show'),
                        'icon' => 'glyphicon glyphicon-eye-open',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('sg.datatables.actions.show'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    ),
                    array(
                        'route' => '<?= $route_pref ?>_edit',
                        'route_parameters' => array(
                            'id' => 'id'
                        ),
                        'label' => $this->translator->trans('sg.datatables.actions.edit'),
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => array(
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('sg.datatables.actions.edit'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ),
                    )
                )
        ));
    }

    /**
    * {@inheritdoc}
    */
    public function getEntity()
    {
        return '<?= $bounded_full_class_name ?>';
    }

    /**
    * {@inheritdoc}
    */
    public function getName()
    {
        return '<?= $datatable_name ?>';
    }
}