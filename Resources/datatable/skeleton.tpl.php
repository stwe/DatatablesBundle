<?= "<?php\n" ?>

namespace <?= $namespace; ?>;

use Sg\DatatablesBundle\Datatable\AbstractDatatable;
use Sg\DatatablesBundle\Datatable\Style;
use Sg\DatatablesBundle\Datatable\Column\Column;
use Sg\DatatablesBundle\Datatable\Column\BooleanColumn;
use Sg\DatatablesBundle\Datatable\Column\ActionColumn;
use Sg\DatatablesBundle\Datatable\Column\MultiselectColumn;
use Sg\DatatablesBundle\Datatable\Column\VirtualColumn;
use Sg\DatatablesBundle\Datatable\Column\DateTimeColumn;
use Sg\DatatablesBundle\Datatable\Column\ImageColumn;
use Sg\DatatablesBundle\Datatable\Filter\TextFilter;
use Sg\DatatablesBundle\Datatable\Filter\NumberFilter;
use Sg\DatatablesBundle\Datatable\Filter\SelectFilter;
use Sg\DatatablesBundle\Datatable\Filter\DateRangeFilter;
use Sg\DatatablesBundle\Datatable\Editable\CombodateEditable;
use Sg\DatatablesBundle\Datatable\Editable\SelectEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextareaEditable;
use Sg\DatatablesBundle\Datatable\Editable\TextEditable;

/**
* Class <?= $class_name . "\n"; ?>
* @package <?= $namespace . "\n"; ?>
*/
class <?= $class_name; ?> extends AbstractDatatable<?= "\n" ?>
{
    /**
     * {@inheritdoc}
     */
    public function buildDatatable(array $options = [])
    {
        $this->language->set([
            'cdn_language_by_locale' => true
        ]);

        $this->ajax->set([
        ]);

        $this->options->set([
            'individual_filtering' => true,
            'individual_filtering_position' => 'head',
            'order_cells_top' => true,
        ]);

        $this->features->set([
        ]);

        $this->columnBuilder
<?php foreach($fields as $field): ?>
            ->add('<?= $field['property'] ?>', <?= $field['column_type'] ?>, [
                'title' => '<?= $field['title'] ?>',
<?php if (!is_null($field['data'])): ?>
                    'data' => '<?= $field['data'] ?>'
<?php endif; ?>
                ])
<?php endforeach; ?>
            ->add(null, ActionColumn::class, [
                'title' => $this->translator->trans('sg.datatables.actions.title'),
                'actions' => [
                    [
                        'route' => '<?= $route_pref ?>_show',
                        'route_parameters' => [
                            'id' => 'id'
                        ],
                        'label' => $this->translator->trans('sg.datatables.actions.show'),
                        'icon' => 'glyphicon glyphicon-eye-open',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('sg.datatables.actions.show'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ],
                    ],
                    [
                        'route' => '<?= $route_pref ?>_edit',
                        'route_parameters' => [
                            'id' => 'id'
                        ],
                        'label' => $this->translator->trans('sg.datatables.actions.edit'),
                        'icon' => 'glyphicon glyphicon-edit',
                        'attributes' => [
                            'rel' => 'tooltip',
                            'title' => $this->translator->trans('sg.datatables.actions.edit'),
                            'class' => 'btn btn-primary btn-xs',
                            'role' => 'button'
                        ],
                    ]
                ]
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity()
    {
        return '<?= $entity_full_class_name ?>';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return '<?= $datatable_name ?>';
    }
}
