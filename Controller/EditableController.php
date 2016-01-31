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

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

/**
 * Class EditableController
 *
 * @package Sg\DatatablesBundle\Controller
 */
class EditableController extends Controller
{
    /**
     * Edit field.
     *
     * @param Request $request
     *
     * @Route("/sg/datatables/edit", name="sg_datatables_edit")
     * @Method("POST")
     *
     * @return Response
     */
    public function editAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $entityName = $request->request->get('entity');
            $field = $request->request->get('name');
            $id = $request->request->get('pk');
            $value = $request->request->get('value');
            $token = $request->request->get('token');

            $setter = 'set' . ucfirst($field);

            if (!$this->isCsrfTokenValid('editable', $token)) {
                // @todo: invalid token
            }

            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository($entityName)->find($id);
            $entity->$setter($value);
            $em->persist($entity);
            $em->flush();

            return new Response('Success', 200);
        }

        return new Response('Bad request', 400);
    }

    /**
     * String to boolean.
     *
     * @param string $str
     *
     * @return bool
     * @throws \Exception
     */
    private function strToBool($str)
    {
        if ($str === 'true') {
            return true;
        } else if ($str === 'false') {
            return false;
        } else {
            throw new \Exception('strToBool(): Cannot convert string to boolean, expected string "true" or "false".');
        }
    }
}
