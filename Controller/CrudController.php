<?php

/**
 * This file is part of the SgDatatablesBundle package.
 *
 * (c) stwe <https://github.com/stwe/DatatablesBundle>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Sg\DatatablesBundle\Controller;

use Sg\DatatablesBundle\Datatable\View\DatatableViewInterface;
use Sg\DatatablesBundle\Routing\DatatablesRoutingLoader;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\ORM\Mapping\MappingException;
use Exception;

/**
 * Class CrudController
 *
 * @package Sg\DatatablesBundle\Controller
 */
class CrudController extends Controller
{
    //-------------------------------------------------
    // Helper
    //-------------------------------------------------

    /**
     * Get datatable.
     *
     * @return DatatableViewInterface
     * @throws Exception
     */
    protected function getDatatable()
    {
        $request = $this->container->get('request');
        $datatableName = $request->get('datatable');
        $container = $this->container->get('sg_datatables.view.container');

        /** @var DatatableViewInterface $datatable */
        $datatable = $container->getDatatableByName($datatableName);
        if (null === $datatable) {
            throw new Exception('getDatatable(): The datatable ' . $datatableName . ' does not exist.');
        }

        $datatable->buildDatatable();

        return $datatable;
    }

    /**
     * Get metadata.
     *
     * @param string $entity
     *
     * @return ClassMetadata
     * @throws Exception
     */
    protected function getMetadata($entity)
    {
        try {
            $metadata = $this->getDoctrine()->getManager()->getMetadataFactory()->getMetadataFor($entity);
        } catch (MappingException $e) {
            throw new Exception('getMetadata(): Given ' . $entity . ' is not a Doctrine Entity.');
        }

        return $metadata;
    }

    /**
     * Get fields.
     *
     * @param ClassMetadata $metadata
     * @param array         $options
     * @param string        $action
     *
     * @return array
     */
    protected function getFields(ClassMetadata $metadata, array $options, $action)
    {
        if (isset($options['fields'][$action]) && !empty($options['fields'][$action])) {
            $fields = $options['fields'][$action];
        } else {
            $fields = $metadata->getFieldNames();
        }

        return $fields;
    }

    /**
     * Get custom form type.
     *
     * @param array  $options
     * @param string $action
     *
     * @return string|null
     */
    protected function getCustomFormType(array $options, $action)
    {
        $type = null;
        if (isset($options['form_types'][$action]) && !empty($options['form_types'][$action])) {
            $type = $options['form_types'][$action];
        }

        return $type;
    }

    /**
     * Get mappings.
     *
     * @param ClassMetadata $metadata
     * @param array         $fields
     *
     * @return array
     * @throws MappingException
     */
    protected function getMappings(ClassMetadata $metadata, array $fields)
    {
        $mappings = array();
        foreach ($fields as $field) {
            try {
                // Gets the mapping of a regular field
                $mappings[$field] = $metadata->getFieldMapping($field);
            } catch (MappingException $e) {
                // Gets the mapping of an association
                $mappings[$field] = $metadata->getAssociationMapping($field);
            }
        }

        return $mappings;
    }

    /**
     * New entity instance.
     *
     * @param ClassMetadata $metadata
     *
     * @return mixed
     */
    protected function newEntityInstance(ClassMetadata $metadata)
    {
        $entityName = $metadata->getName();
        $entity = new $entityName;

        return $entity;
    }

    /**
     * Get entity by id.
     *
     * @param array      $options
     * @param mixed      $id
     * @param null|array $fields
     * @param bool       $asArray
     *
     * @return array|object
     * @throws Exception
     */
    protected function getEntity(array $options, $id, $fields = null, $asArray = false)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($options['class'])->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('getEntity(): Unable to find the object with id ' . $id);
        }

        if (true === $asArray) {
            $array = array();
            $methods = get_class_methods($entity);

            if (!empty($fields)) {
                foreach ($fields as $field) {
                    $method = 'get' . ucfirst($field);
                    if (in_array($method, $methods)) {
                        $array[$field] = $entity->$method();
                    } else {
                        throw new Exception('getEntity(): ' . $method . ' invalid method name');
                    }
                }
            }

            return $array;
        }

        return $entity;
    }

    //-------------------------------------------------
    // Actions
    //-------------------------------------------------

    /**
     * Homepage.
     *
     * @return Response
     */
    public function homeAction()
    {
        return $this->render('SgDatatablesBundle:Crud:home.html.twig');
    }

    /**
     * Lists all entities.
     *
     * @return Response
     */
    public function indexAction()
    {
        $datatable = $this->getDatatable();

        return $this->render(
            'SgDatatablesBundle:Crud:index.html.twig',
            array(
                'datatable' => $datatable
            )
        );
    }

    /**
     * Index results action.
     *
     * @return Response
     */
    public function indexResultsAction()
    {
        $datatable = $this->getDatatable();
        $datatable = $this->container->get('sg_datatables.query')->getQueryFrom($datatable);

        return $datatable->getResponse();
    }

    /**
     * Creates a new entity.
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function createAction(Request $request)
    {
        $options = $request->get('options');
        $alias = $request->get('alias');

        $metadata = $this->getMetadata($options['class']);
        $entity = $this->newEntityInstance($metadata);

        $fields = $this->getFields($metadata, $options, 'new');
        $type = $this->getCustomFormType($options, 'new');

        $form = $this->createCreateForm($entity, $alias, $fields, $type);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl(DatatablesRoutingLoader::PREF . $alias . '_show', array('id' => $entity->getId())));
        }

        return $this->render(
            'SgDatatablesBundle:Crud:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
                'list_action' => DatatablesRoutingLoader::PREF . $alias . '_index'
            )
        );
    }

    /**
     * Creates a form to create an entity.
     *
     * @param object      $entity
     * @param string      $alias
     * @param array       $fields
     * @param string|null $type
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity, $alias, array $fields, $type)
    {
        if (null !== $type) {
            $form = $this->createForm(new $type, $entity, array(
                'action' => $this->generateUrl(DatatablesRoutingLoader::PREF . $alias . '_create'),
                'method' => 'POST',
            ));

            $form->add('submit', 'submit', array('label' => $this->get('translator')->trans('datatables.actions.save'), 'attr' => array('class' => 'btn btn-primary')));

            return $form;
        }

        /** @var \Symfony\Component\Form\FormBuilder $formBuilder */
        $formBuilder = $this->container->get('form.factory')->createBuilder('form', $entity);
        $formBuilder->setAction($this->generateUrl(DatatablesRoutingLoader::PREF . $alias . '_create'));
        $formBuilder->setMethod('POST');

        foreach ($fields as $field) {
            $formBuilder->add($field);
        };

        $formBuilder->add('submit', 'submit', array('label' => $this->get('translator')->trans('datatables.actions.save'), 'attr' => array('class' => 'btn btn-primary')));

        return $formBuilder->getForm();
    }

    /**
     * Displays a form to create a new entity.
     *
     * @return Response
     */
    public function newAction()
    {
        $request = $this->container->get('request');
        $options = $request->get('options');
        $alias = $request->get('alias');

        $metadata = $this->getMetadata($options['class']);
        $entity = $this->newEntityInstance($metadata);

        $fields = $this->getFields($metadata, $options, 'new');
        $type = $this->getCustomFormType($options, 'new');

        $form = $this->createCreateForm($entity, $alias, $fields, $type);

        return $this->render(
            'SgDatatablesBundle:Crud:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView(),
                'list_action' => DatatablesRoutingLoader::PREF . $alias . '_index'
            )
        );
    }

    /**
     * Finds and displays an entity.
     *
     * @param integer $id
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function showAction($id)
    {
        $request = $this->container->get('request');
        $options = $request->get('options');
        $alias = $request->get('alias');

        $metadata = $this->getMetadata($options['class']);

        $fields = $this->getFields($metadata, $options, 'show');
        $entity = $this->getEntity($options, $id, $fields, true);
        $mappings = $this->getMappings($metadata, $fields);

        $deleteForm = $this->createDeleteForm($id, $alias);

        return $this->render(
            'SgDatatablesBundle:Crud:show.html.twig',
            array(
                'entity' => $entity,
                'fields' => $fields,
                'mappings' => $mappings,
                'delete_form' => $deleteForm->createView(),
                'list_action' => DatatablesRoutingLoader::PREF . $alias . '_index'
            )
        );
    }

    /**
     * Displays a form to edit an existing entity.
     *
     * @param integer $id
     *
     * @return Response
     * @throws NotFoundHttpException
     */
    public function editAction($id)
    {
        $request = $this->container->get('request');
        $options = $request->get('options');
        $alias = $request->get('alias');

        $metadata = $this->getMetadata($options['class']);
        $entity = $this->getEntity($options, $id);

        $fields = $this->getFields($metadata, $options, 'edit');
        $type = $this->getCustomFormType($options, 'edit');

        $editForm = $this->createEditForm($entity, $alias, $fields, $type);

        return $this->render(
            'SgDatatablesBundle:Crud:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'list_action' => DatatablesRoutingLoader::PREF . $alias . '_index'
            )
        );
    }

    /**
     * Creates a form to edit an entity.
     *
     * @param object      $entity
     * @param string      $alias
     * @param array       $fields
     * @param string|null $type
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm($entity, $alias, array $fields, $type)
    {
        if (null !== $type) {
            $form = $this->createForm(new $type, $entity, array(
                'action' => $this->generateUrl(DatatablesRoutingLoader::PREF . $alias . '_update', array('id' => $entity->getId())),
                'method' => 'PUT',
            ));

            $form->add('submit', 'submit', array('label' => $this->get('translator')->trans('datatables.actions.save'), 'attr' => array('class' => 'btn btn-primary')));

            return $form;
        }

        /** @var \Symfony\Component\Form\FormBuilder $formBuilder */
        $formBuilder = $this->container->get('form.factory')->createBuilder('form', $entity);
        $formBuilder->setAction($this->generateUrl(DatatablesRoutingLoader::PREF . $alias . '_update', array('id' => $entity->getId())));
        $formBuilder->setMethod('PUT');

        foreach ($fields as $field) {
            $formBuilder->add($field);
        };

        $formBuilder->add('submit', 'submit', array('label' => $this->get('translator')->trans('datatables.actions.save'), 'attr' => array('class' => 'btn btn-primary')));

        return $formBuilder->getForm();
    }

    /**
     * Edits an existing entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @return RedirectResponse|Response
     * @throws NotFoundHttpException
     */
    public function updateAction(Request $request, $id)
    {
        $options = $request->get('options');
        $alias = $request->get('alias');

        $metadata = $this->getMetadata($options['class']);
        $entity = $this->getEntity($options, $id);

        $fields = $this->getFields($metadata, $options, 'edit');
        $type = $this->getCustomFormType($options, 'edit');

        $editForm = $this->createEditForm($entity, $alias, $fields, $type);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();

            return $this->redirect($this->generateUrl(DatatablesRoutingLoader::PREF . $alias . '_show', array('id' => $entity->getId())));
        }

        return $this->render(
            'SgDatatablesBundle:Crud:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView(),
                'list_action' => DatatablesRoutingLoader::PREF . $alias . '_index'
            )
        );
    }

    /**
     * Deletes an entity.
     *
     * @param Request $request
     * @param integer $id
     *
     * @return RedirectResponse|Response
     * @throws NotFoundHttpException
     */
    public function deleteAction(Request $request, $id)
    {
        $alias = $request->get('alias');

        $form = $this->createDeleteForm($id, $alias);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $options = $request->get('options');
            $entity = $this->getEntity($options, $id);

            $em = $this->getDoctrine()->getManager();
            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl(DatatablesRoutingLoader::PREF . $alias . '_index'));
    }

    /**
     * Creates a form to delete an entity by id.
     *
     * @param integer $id
     * @param string  $alias
     *
     * @return \Symfony\Component\Form\Form
     */
    private function createDeleteForm($id, $alias)
    {
        /** @var \Symfony\Component\Form\FormBuilder $formBuilder */
        $formBuilder = $this->container->get('form.factory')->createBuilder('form');
        $formBuilder->setAction($this->generateUrl(DatatablesRoutingLoader::PREF . $alias . '_delete', array('id' => $id)));
        $formBuilder->setMethod('DELETE');
        $formBuilder->add('submit', 'submit', array('label' => $this->get('translator')->trans('datatables.actions.delete'), 'attr' => array('class' => 'btn btn-danger')));

        return $formBuilder->getForm();
    }
} 
