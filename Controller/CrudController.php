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
    private function getDatatable()
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
     * Get alias.
     *
     * @return string
     */
    private function getAlias()
    {
        $request = $this->container->get('request');

        return $request->get('alias');
    }

    /**
     * Get fields.
     *
     * @param string $action
     *
     * @return array
     * @throws Exception
     */
    private function getFields($action)
    {
        $request = $this->container->get('request');
        $fields = $request->get('fields');

        $array = $this->searchSubArray($fields, 'route', $this->getAlias());

        if (null === $array) {
            throw new Exception('getFields(): Config error.');
        }

        if (false === array_key_exists($action, $array)) {
            throw new Exception('getFields(): Config error.');
        }

        return $array[$action];
    }

    /**
     * Get role.
     *
     * @param string $action
     *
     * @return mixed
     * @throws Exception
     */
    private function getRole($action)
    {
        $request = $this->container->get('request');
        $roles = $request->get('roles');

        $array = $this->searchSubArray($roles, 'route', $this->getAlias());

        if (null === $array) {
            throw new Exception('getRole(): Config error.');
        }

        if (false === array_key_exists($action, $array)) {
            throw new Exception('getRole(): Config error.');
        }

        return $array[$action];
    }

    /**
     * Get entity.
     *
     * @return object
     * @throws Exception
     */
    private function getNewEntity()
    {
        $datatable = $this->getDatatable();
        $entityName = $datatable->getEntity();

        $metadata = $this->getDoctrine()->getManager()->getClassMetadata($entityName);
        $className = $metadata->getName();
        return new $className;
    }

    /**
     * Search sub array.
     *
     * @param array  $array
     * @param string $key
     * @param string $value
     *
     * @return array|null
     */
    private function searchSubArray(array $array, $key, $value) {
        foreach ($array as $subarray){
            if (isset($subarray[$key]) && $subarray[$key] == $value)
                return $subarray;
        }

        return null;
    }

    //-------------------------------------------------
    // Actions
    //-------------------------------------------------

    /**
     * Lists all entities.
     *
     * @return Response
     */
    public function indexAction()
    {
        $this->denyAccessUnlessGranted($this->getRole('index'), null, 'Unable to access this page!');

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
        $this->denyAccessUnlessGranted($this->getRole('index'), null, 'Unable to access this page!');

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
        $this->denyAccessUnlessGranted($this->getRole('new'), null, 'Unable to access this page!');

        $entity = $this->getNewEntity();
        $alias = $this->getAlias();
        $fields = $this->getFields('new');

        $form = $this->createCreateForm($entity, $alias, $fields);
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
                'form' => $form->createView()
            )
        );
    }

    /**
     * Creates a form to create an entity.
     *
     * @param object $entity
     * @param string $alias
     * @param array  $fields
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm($entity, $alias, array $fields)
    {
        /** @var \Symfony\Component\Form\FormBuilder $formBuilder */
        $formBuilder = $this->container->get('form.factory')->createBuilder('form', $entity);
        $formBuilder->setAction($this->generateUrl(DatatablesRoutingLoader::PREF . $alias . '_create'));
        $formBuilder->setMethod('POST');

        foreach ($fields as $field) {
            $formBuilder->add($field);
        };

        $formBuilder->add('submit', 'submit', array('label' => 'Create', 'attr' => array('class' => 'btn btn-primary')));

        return $formBuilder->getForm();
    }

    /**
     * Displays a form to create a new entity.
     *
     * @return Response
     */
    public function newAction()
    {
        $this->denyAccessUnlessGranted($this->getRole('new'), null, 'Unable to access this page!');

        $entity = $this->getNewEntity();
        $alias = $this->getAlias();
        $fields = $this->getFields('new');

        $form = $this->createCreateForm($entity, $alias, $fields);

        return $this->render(
            'SgDatatablesBundle:Crud:new.html.twig',
            array(
                'entity' => $entity,
                'form' => $form->createView()
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
        $this->denyAccessUnlessGranted($this->getRole('show'), null, 'Unable to access this page!');

        $datatable = $this->getDatatable();
        $alias = $this->getAlias();

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($datatable->getEntity())->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Unable to find the object with id ' . $id);
        }

        $deleteForm = $this->createDeleteForm($id, $alias);

        return $this->render(
            'SgDatatablesBundle:Crud:show.html.twig',
            array(
                'entity' => $entity,
                'fields' => $this->getFields('show'),
                'delete_form' => $deleteForm->createView()
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
        $this->denyAccessUnlessGranted($this->getRole('edit'), null, 'Unable to access this page!');

        $datatable = $this->getDatatable();

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($datatable->getEntity())->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Unable to find the object with id ' . $id);
        }

        $editForm = $this->createEditForm($entity, $this->getAlias(), $this->getFields('edit'));

        return $this->render(
            'SgDatatablesBundle:Crud:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView()
            )
        );
    }

    /**
     * Creates a form to edit an entity.
     *
     * @param object $entity
     * @param string $alias
     * @param array  $fields
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createEditForm($entity, $alias, array $fields)
    {
        /** @var \Symfony\Component\Form\FormBuilder $formBuilder */
        $formBuilder = $this->container->get('form.factory')->createBuilder('form', $entity);
        $formBuilder->setAction($this->generateUrl(DatatablesRoutingLoader::PREF . $alias . '_update', array('id' => $entity->getId())));
        $formBuilder->setMethod('PUT');

        foreach ($fields as $field) {
            $formBuilder->add($field);
        };

        $formBuilder->add('submit', 'submit', array('label' => 'Update', 'attr' => array('class' => 'btn btn-primary')));

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
        $this->denyAccessUnlessGranted($this->getRole('edit'), null, 'Unable to access this page!');

        $datatable = $this->getDatatable();
        $alias = $this->getAlias();

        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository($datatable->getEntity())->find($id);

        if (!$entity) {
            throw new NotFoundHttpException('Unable to find the object with id ' . $id);
        }

        $editForm = $this->createEditForm($entity, $alias, $this->getFields('edit'));
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl(DatatablesRoutingLoader::PREF . $alias . '_show', array('id' => $entity->getId())));
        }

        return $this->render(
            'SgDatatablesBundle:Crud:edit.html.twig',
            array(
                'entity' => $entity,
                'edit_form' => $editForm->createView()
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
        $this->denyAccessUnlessGranted($this->getRole('delete'), null, 'Unable to access this page!');

        $alias = $this->getAlias();

        $form = $this->createDeleteForm($id, $alias);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $datatable = $this->getDatatable();

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository($datatable->getEntity())->find($id);

            if (!$entity) {
                throw new NotFoundHttpException('Unable to find the object with id ' . $id);
            }

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
        $formBuilder->add('submit', 'submit', array('label' => 'Delete', 'attr' => array('class' => 'btn btn-danger')));

        return $formBuilder->getForm();
    }
} 
