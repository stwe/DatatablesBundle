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
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
     * @Route("/sg/datatables/edit/field", name="sg_datatables_edit")
     * @Method("POST")
     *
     * @return Response
     * @throws \Exception
     */
    public function editAction(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $entityName = $request->request->get('entity');
            $field = $request->request->get('name');
            $id = $request->request->get('pk');
            $value = $request->request->get('value');
            $token = $request->request->get('token');

            $supportedFieldType = false;
            $fieldType = null;
            $getter = null;
            $setter = null;

            if (!$this->isCsrfTokenValid('editable', $token)) {
                throw new AccessDeniedException('The CSRF token is invalid.');
            }

            $em = $this->getDoctrine()->getManager();
            $metadata = $em->getClassMetadata($entityName);

            if (false === strstr($field, '.')) {
                $setter = 'set' . ucfirst($field);
                $fieldType = $metadata->getTypeOfField($field);
            } else {
                $parts = explode('.', $field);
                $getter = 'get' . ucfirst($parts[0]);
                $setter = 'set' . ucfirst($parts[1]);
                $targetClass = $metadata->getAssociationTargetClass($parts[0]);
                $targetMeta = $em->getClassMetadata($targetClass);
                $fieldType = $targetMeta->getTypeOfField($parts[1]);
            }

            $entity = $em->getRepository($entityName)->find($id);
            if (!$entity) {
                throw $this->createNotFoundException('The entity does not exist.');
            }

            switch ($fieldType) {
                case 'datetime':
                    $dateTime = new \DateTime($value);
                    null === $getter ? $entity->$setter($dateTime) : $entity->$getter()->$setter($dateTime);
                    $supportedFieldType = true;
                    break;
                case 'boolean':
                    null === $getter ? $entity->$setter($this->strToBool($value)) : $entity->$getter()->$setter($this->strToBool($value));
                    $supportedFieldType = true;
                    break;
                case 'string':
                    null === $getter ? $entity->$setter($value) : $entity->$getter()->$setter($value);
                    $supportedFieldType = true;
                    break;
                case 'integer':
                    null === $getter ? $entity->$setter((int) $value) : $entity->$getter()->$setter((int) $value);
                    $supportedFieldType = true;
                    break;
                default:
                    throw new \Exception("editAction(): The field type {$fieldType} is not supported.");
            }

            if (true === $supportedFieldType) {
                $em->persist($entity);
                $em->flush();
            }

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
